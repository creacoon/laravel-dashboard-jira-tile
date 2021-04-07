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
        #$project = jira()->projects()->get("CDASH");

        //Make filter
        $request = app(\Atlassian\JiraRest\Requests\Issue\IssueRequest::class);
        $response = $request->search([
            'maxResults' => 10,
            'startAt' => 0,
            #'fields' => "summary, comment, status, assignee, avatarUrls, project",
            'jql' => 'status="In Progress"'
        ]);

        //Format
        $output = \json_decode($response->getBody()->getContents(), true);

        //Place in 1 array
        $jiraData = [];
        $i = 0;
        foreach ($output["issues"] as $issue){
            $jiraData[$i] = [
                'key' => $issue["key"],
                'title' => $issue["fields"]["summary"],
                'prImg' => $issue["fields"]["project"]["avatarUrls"]["48x48"],
                'asImg' => $issue["fields"]["assignee"]["avatarUrls"]["48x48"],
            ];
            $i++;
        }

        //Store data
        JiraStore::make()->setData($jiraData);
        $this->info('All done!');
    }
}
