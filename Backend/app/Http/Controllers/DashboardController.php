<?php

namespace App\Http\Controllers;

use App\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDashboardData(Request $request)
    {

        $allCourses = Course::get();

        $mostPopularCourses = $this->getMostPopularCourses($allCourses);
        $bestRatedCourses = $this->getBestRatedCourses($allCourses);

        return response([
            'mostPopularCourses' => $mostPopularCourses,
            'bestRatedCourses' => $bestRatedCourses,
        ]);
    }

    /**
     * @param $allCourses
     * @return array
     */
    private function getMostPopularCourses($allCourses): array
    {
        $mostPopularCourses = [];

        foreach ($allCourses as $course) {

            $students = $course->users->filter(function ($user) {
                return $user->hasRole('student');
            });

            $mostPopularCourses[] = [
                'course' => $course,
                'students' => count($students)
            ];
        }

        $mostPopularCourses = Collection::make($mostPopularCourses)->sortBy(function ($value) {
            return $value['students'];
        }, SORT_REGULAR, true)->all();
        $mostPopularCourses = array_values($mostPopularCourses);
        return $mostPopularCourses;
    }

    /**
     * @param $allCourses
     * @return array
     */
    private function getBestRatedCourses($allCourses): array
    {
        $bestRatedCourses = [];

        foreach ($allCourses as $course) {
            $bestRatedCourses[] = [
                'course' => $course,
                'score' => $this->getCourseScore($course)
            ];
        }

        $bestRatedCourses = Collection::make($bestRatedCourses)->sortBy(function ($value) {
            return $value['score'];
        }, SORT_REGULAR, true)->all();
        $bestRatedCourses = array_values($bestRatedCourses);
        return $bestRatedCourses;
    }

    /**
     * @param $course
     * @return float
     */
    private function getCourseScore($course): float
    {
        $score = 0;
        $total = count($course->usersThatReviewed);

        foreach ($course->usersThatReviewed as $review) {
            $score += $review->pivot->score;
        }

        $score = $total == 0 ? 0 : $score / $total;

        return round($score, 2);
    }
}
