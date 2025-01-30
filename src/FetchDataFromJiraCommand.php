<?php

namespace Creacoon\JiraTile;

use Atlassian\JiraRest\Exceptions\JiraClientException;
use Atlassian\JiraRest\Exceptions\JiraNotFoundException;
use Atlassian\JiraRest\Exceptions\JiraUnauthorizedException;
use Atlassian\JiraRest\Facades\Jira;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;

class FetchDataFromJiraCommand extends Command
{
    protected $signature = 'dashboard:fetch-data-from-jira-api';

    protected $description = 'Fetch data for tile';

    public function handle()
    {
        $maxResults = config('dashboard.tiles.jira.max_results');
        $jql = config('dashboard.tiles.jira.jql');
        $request = app(\Atlassian\JiraRest\Requests\Issue\IssueRequest::class);
        $response = $request->search([
            'maxResults' => $maxResults,
            'startAt' => 0,
            'jql' => $jql,
        ]);

        $output = \json_decode($response->getBody()->getContents(), true);

        $jiraData = [];
        $i = 0;
        foreach ($output["issues"] as $issue) {
            $nameArray = (isset($issue["fields"]["assignee"]["displayName"]) ?
                explode(' ', $issue["fields"]["assignee"]["displayName"]) : []);

            $initials = '';

            if ($nameArray){
                $initials = substr($nameArray[0], 0, 1).
                    (($nameArray[1] ?? false) ? substr($nameArray[(count($nameArray)-1)], 0, 1) : '');
            }

            $jiraData[$i] = [
                'key' => $issue["key"],
                'title' => $issue["fields"]["summary"],
                'asImg' => $issue["fields"]["assignee"]["avatarUrls"]["48x48"] ?? null,
                'asInitials' => $initials,
            ];
            $i++;
        }

        JiraStore::make()->setData($jiraData);
        $this->info('All done!');
    }
}
