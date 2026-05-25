@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex gap-2 items-center justify-between">

        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-slate-300 bg-white border border-gray-300 cursor-not-allowed leading-5 rounded-md dark:text-slate-600 dark:bg-slate-200 dark:border-gray-600">
                {!! __('pagination.previous') !!}
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-800 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-700 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-800 transition ease-in-out duration-150 dark:bg-slate-100 dark:border-gray-600 dark:text-slate-700 dark:focus:border-blue-700 dark:active:bg-slate-200 dark:active:text-slate-600 hover:bg-gray-100 dark:hover:bg-white dark:hover:text-slate-700">
                {!! __('pagination.previous') !!}
            </a>
        @endif

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-800 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-700 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-800 transition ease-in-out duration-150 dark:bg-slate-100 dark:border-gray-600 dark:text-slate-700 dark:focus:border-blue-700 dark:active:bg-slate-200 dark:active:text-slate-600 hover:bg-gray-100 dark:hover:bg-white dark:hover:text-slate-700">
                {!! __('pagination.next') !!}
            </a>
        @else
            <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-slate-300 bg-white border border-gray-300 cursor-not-allowed leading-5 rounded-md dark:text-slate-600 dark:bg-slate-200 dark:border-gray-600">
                {!! __('pagination.next') !!}
            </span>
        @endif

    </nav>
@endif
