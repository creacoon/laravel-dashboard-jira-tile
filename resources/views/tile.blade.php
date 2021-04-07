<x-dashboard-tile :position="$position" :refresh-interval="$refreshIntervalInSeconds">
    <div class="grid grid-rows-auto-1 gap-2 h-full">
        <div class="flex items-center justify-center">
            <div class="font-medium text-dimmed text-sm uppercase tracking-wide tabular-nums">
                Jira in progress
            </div>
        </div>
        <div wire:poll.{{ $refreshIntervalInSeconds }}s>
            <div class="grid gap-3 sm:grid-cols-1 md:grid-cols-2">
                @foreach($jiraData as $issue)
                    <div>
                        <div class="pl-3 pt-1 pb-1 rounded-t-lg flex" style="font-size: 1.4rem; background-color: rgb(0, 82, 204);">
                            <div class="w-5/6">{{$issue["key"]}}</div>
                            <img class="w-1/6 rounded-full" src="{{$issue["asImg"]}}" style="max-width: 2rem">
                        </div>
                        <div class="rounded-b-lg" style="border: 1px solid rgb(0, 82, 204); border-top: 0; color:white;">
                            <div class="pl-3" style="margin: 0 0.05rem 0.05rem 0.05rem">
                                {{$issue["title"]}}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-dashboard-tile>