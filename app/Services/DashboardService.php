<?php

namespace App\Services;

use App\Commons\Utils;
use App\Models\Solution;
use App\Models\SolutionMentorship;
use App\Models\Startup;
use App\User;
use Carbon\Carbon;
use function foo\func;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    private $user;
    private $startup;
    private $solution;
    public function __construct(User $user, Startup $startup, Solution $solution)
    {
         //echo "ssss";
        /* echo "<pre/>";
        print_r($startup);
        die('sss');*/
       // $this->user = $user;
      /*  echo "<pre/>";
        print_r($a);
        echo "<pre/>";*/
       // $this->startup = $startup;
       /*  print_r($b);
         echo "<pre/>";*/
        //$this->solution = $solution;
        // print_r($c);
    }

    public function getSummary()
    {

        $summary = [];
        $solutions = $this->getAllSolutions();
        $startups = $this->getAllStartups();
        $data = $this->getAllTeamMembers();

        $summary['startups']['last_year'] = $startups['last_year'];
        $summary['startups']['this_year'] = $startups['this_year'];
        $summary['startups']['total_count']=$startups['last_year']+$startups['this_year'];

        $summary['solutions']= $this->getSolutionByStatus()==null?[]:$this->getSolutionByStatus();
        $summary['solutions']['total_count'] = $solutions;

        $summary['team_members']['total_count']=$data['total_count'];
        $summary['team_members']['famale_count']=$data['female_count'];
        $summary['team_members']['male_count']=$data['male_count'];
        return $summary;
    }

    private function getSolutionByStatus()
    {
        if (Utils::getLoggedInUser(request()->user()->id)->type == 'admin') {
            $data = DB::table('solutions')
                ->join('startups', 'startup_id', 'startups.id')
                ->join('solution_statuses', 'status_id', '=', 'solution_statuses.id')
                ->select(DB::raw('solution_statuses.name as status,count(*) as count'))
                ->groupBy('status')
                ->get();
            return $data->count() == 0?null:$this->formatSolutionsGroupByStatus($data);
        } elseif (Utils::getLoggedInUser(request()->user()->id)->type == 'mentor') {
            $data = DB::table('solutions')
                ->join('startups', 'startup_id', 'startups.id')
                ->join('solution_statuses', 'status_id', '=', 'solution_statuses.id')
                ->join('solution_mentors', 'solutions.id', '=', 'solution_mentors.solution_id')
                ->where('solution_mentors.mentor_id', request()->user()->id)
                ->select(DB::raw('solution_statuses.name as status,count(*) as count'))
                ->groupBy('status')
                ->get();
            return $data->count() == 0?null:$this->formatSolutionsGroupByStatus($data);
        } else {
            $data = DB::table('solutions')
                ->join('startups', 'startup_id', 'startups.id')
                ->join('solution_statuses', 'status_id', '=', 'solution_statuses.id')
                ->select(DB::raw('solution_statuses.name as status,count(*) as count'))
                ->where('startups.owner_id', request()->user()->id)
                ->groupBy('status')
                ->get();
            return $data->count() == 0?null:$this->formatSolutionsGroupByStatus($data);
        }
    }

    private function formatSolutionsGroupByStatus(Collection $details)
    {
        $data= collect([]);
        $details->each(function ($item, $key) use ($data) {
            $data->put($item->status, $item->count);
        });
        return $data->toArray();
    }

    private function getAllStartups()
    {
        $data = [];
        $year = (string)Carbon::now()->year;
        $last_year = (string)Carbon::now()->subYear(1)->year;
        if (Utils::getLoggedInUser(request()->user()->id)->type == 'admin') {
            $data['this_year'] = $this->startup->whereYear('created_at', $year)->count();
            $data['last_year'] = $this->startup->WhereYear('created_at', $last_year)->count();
        } elseif (Utils::getLoggedInUser(request()->user()->id)->type == 'mentor') {
            $query = Startup::query()->whereHas('solution', function ($query) {
                $query->whereHas('mentorships', function ($query) {
                    $query->where('mentor_id', request()->user()->id);
                });
            });
            $data['this_year'] = $query->whereYear('created_at', $year)->count();
            $data['last_year'] = $query->whereYear('created_at', $last_year)->count();
        } else {
            $data['this_year'] = $this->startup->owner()->whereYear('created_at', $year)->count();
            $data['last_year'] = $this->startup->owner()->WhereYear('created_at', $last_year)->count();
        }
        return $data;
    }

    private function getAllSolutions()
    {
        if (Utils::getLoggedInUser(request()->user()->id)->type == 'admin') {
            $solutions=$this->solution->count();
        } elseif (Utils::getLoggedInUser(request()->user()->id)->type == 'mentor') {
            $solutions = Solution::query()->whereHas('mentorships', function ($query) {
                $query->where('mentor_id', request()->user()->id);
            })->count();
        } else {
            $solutions =$this->solution->whereHas('startup', function ($query) {
                $query->where('owner_id', request()->user()->id);
            })->count();
        }
        return $solutions;
    }

    public function getAllTeamMembers()
    {
        if (request()->user()->isAdmin()) {
            $solutions =$this->solution->get(['team']);
        }
        elseif (request()->user()->isMentor()) {
            $solutions = Solution::query()
                ->whereHas('mentorships', function ($query) {
                    $query->where('mentor_id', request()->user()->id);
                })
                ->get(['team']);
        }
        else {
            $solutions =$this->solution
                ->whereHas('startup', function ($query) {
                    $query->where('owner_id', request()->user()->id);
                })
                ->get(['team']);
        }

        // initialize counts
        $male_count = $female_count = $total_count = 0;

        $solutions->each(function ($item, $key) use (&$male_count,&$female_count,&$total_count) {
            $members =$item->team;
            foreach ($members as $member) {
                $total_count++;
                if(!isset($member->gender)) continue; // skip if gender not defined

                if ($member->gender == 'male') {
                    $male_count++;
                } else {
                    $female_count++;
                }
            }
        });

        return [
            'total_count' => $total_count,
            'male_count' => $male_count,
            'female_count' => $female_count,
        ];
    }
}
