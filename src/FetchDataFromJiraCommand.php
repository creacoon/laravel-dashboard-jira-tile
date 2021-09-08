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
        $request = app(\Atlassian\JiraRest\Requests\Issue\IssueRequest::class);
        $response = $request->search([
            'maxResults' => 10,
            'startAt' => 0,
            'jql' => 'status="In Progress"'
        ]);

        $output = \json_decode($response->getBody()->getContents(), true);

        $jiraData = [];
        $i = 0;
        foreach ($output["issues"] as $issue) {
            $nameArray = explode(' ', $issue["fields"]["assignee"]["displayName"]);

            $initials = substr($nameArray[0], 0, 1).
                (($nameArray[1] ?? false) ? substr($nameArray[(count($nameArray)-1)], 0, 1) : '');

            $jiraData[$i] = [
                'key' => $issue["key"],
                'title' => $issue["fields"]["summary"],
                'asImg' => $issue["fields"]["assignee"]["avatarUrls"]["48x48"] ?? null,
                'asInitials' => substr($initials[0],0,1).substr($initials[1],0,1) ?? null,
            ];
            $i++;
        }

        JiraStore::make()->setData($jiraData);
        $this->info('All done!');
    }
}
