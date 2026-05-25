@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'bg-white dark:bg-slate-50 text-gray-900 dark:text-slate-800 border-gray-300 dark:border-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:ring-indigo-500 dark:focus:ring-indigo-500 rounded-xl shadow-sm transition duration-150']) }}>
