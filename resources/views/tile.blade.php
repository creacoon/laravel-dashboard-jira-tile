<x-dashboard-tile :position="$position" :refresh-interval="$refreshIntervalInSeconds">
    <div class="grid grid-rows-auto-1 gap-3 h-full">
        <div class="flex items-center justify-between">
            <div class="font-medium text-dimmed text-sm uppercase tracking-wide">
                Jira in progress
            </div>
            @if(count($jiraData) > 0)
                <div class="rounded-full bg-[rgb(0,82,204)]/25 px-2.5 py-0.5 text-sm font-semibold tabular-nums text-[rgb(76,154,255)]">
                    {{ count($jiraData) }}
                </div>
            @endif
        </div>

        @if(count($jiraData) === 0)
            <div class="flex items-center justify-center text-dimmed text-sm">
                Nothing in progress
            </div>
        @else
            <div class="grid content-start gap-2.5 md:grid-cols-2">
                @foreach($jiraData as $issue)
                    <div class="flex items-center gap-3 rounded-xl border border-white/5 border-l-[3px] border-l-[rgb(76,154,255)] bg-white/[0.04] py-2.5 pl-3 pr-2.5">
                        <div class="min-w-0 grow">
                            <div class="text-xs font-semibold uppercase tracking-wider tabular-nums text-[rgb(76,154,255)]">
                                {{ $issue['key'] }}
                            </div>
                            <div class="mt-0.5 line-clamp-2 text-base font-medium leading-snug text-default">
                                {{ $issue['title'] }}
                            </div>
                        </div>

                        @if($issue['asImg'])
                            <img class="size-9 shrink-0 rounded-full ring-2 ring-white/10" src="{{ $issue['asImg'] }}" alt="{{ $issue['asInitials'] }}">
                        @elseif($issue['asInitials'])
                            <div class="flex size-9 shrink-0 items-center justify-center rounded-full bg-amber-500 text-sm font-semibold text-white ring-2 ring-white/10">
                                {{ $issue['asInitials'] }}
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-dashboard-tile>
