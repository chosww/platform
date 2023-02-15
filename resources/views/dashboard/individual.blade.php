<div class="with-sidebar with-sidebar:2/3">
    <x-quick-links>
        @if ($user->individual->isConnector() ||
            $user->individual->isConsultant() ||
            $user->individual->inProgressContractedProjects)
            <li>
                @if ($user->individual->checkStatus('published'))
                    <a href="{{ localized_route('individuals.show', $user->individual) }}">{{ __('My public page') }}</a>
                @else
                    <a
                        href="{{ localized_route('individuals.edit', $user->individual) }}">{{ __('Edit my public page') }}</a>
                @endif
            </li>
            @can('viewAny', App\Models\Project::class)
                <li>
                    <a
                        href="{{ $user->individual->isParticipant() ? localized_route('projects.my-contracted-projects') : localized_route('projects.my-projects') }}">{{ __('Involved as a Community Connector') }}</a>
                </li>
            @endcan
        @endif
        @if (!$user->oriented_at)
            <li>
                <a href="{{ orientation_link($user->context) }}">{{ __('Sign up for an orientation session') }}</a>
            </li>
        @endif
        @can('viewAny', App\Models\Project::class)
            @if ($user->individual->isParticipant() || $user->individual->inProgressParticipatingProjects)
                <li>
                    <a
                        href="{{ localized_route('projects.my-projects') }}">{{ __('Involved as a Consultation Participant') }}</a>
                </li>
            @endif
        @endcan
    </x-quick-links>
    <div class="border-divider mt-14 mb-12 border-x-0 border-t-3 border-b-0 border-solid pt-6">
        @include('dashboard.partials.notifications', [
            'notifications' => $user->allUnreadNotifications(),
        ])
    </div>
</div>
