<?php
return [
    'max_results' => env('JIRA_TILE_MAX_RESULTS', 10),
    'jql' => env('JIRA_TILE_JQL', 'status="In Progress"'),
];
