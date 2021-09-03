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
        foreach ($output["issues"] as $issue){
            $initials = explode(' ', $issue["fields"]["assignee"]["displayName"]);

            $jiraData[$i] = [
                'key' => $issue["key"],
                'title' => $issue["fields"]["summary"],
                'prImg' => $issue["fields"]["project"]["avatarUrls"]["48x48"],
                'asImg' => $issue["fields"]["assignee"]["avatarUrls"]["48x48"] ?? null,
                'asInitials' => substr($initials[0],0,1).substr($initials[1],0,1),
            ];
            $i++;
        }

        JiraStore::make()->setData($jiraData);
        $this->info('All done!');
    }
}
