<div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
    <a class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 text-gray-900 focus:outline-none transition duration-150 ease-in-out {{ request()->routeIs('dashboard') ? 'border-indigo-400' : '' }}"
        href="{{ route('dashboard') }}">
        Dashboard
    </a>
    <a class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 text-gray-900 focus:outline-none transition duration-150 ease-in-out {{ request()->routeIs('jobs.*') ? 'border-indigo-400' : '' }}"
        href="{{ route('jobs.index') }}">
        Job
    </a>
    <a class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 text-gray-900 focus:outline-none transition duration-150 ease-in-out {{ request()->routeIs('skills.*') ? 'border-indigo-400' : '' }}"
        href="{{ route('skills.index') }}">
        Skill
    </a>
    <a class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 text-gray-900 focus:outline-none transition duration-150 ease-in-out {{ request()->routeIs('skillsets.*') ? 'border-indigo-400' : '' }}"
        href="{{ route('skillsets.index') }}">
        Skill Sets
    </a>
</div>
