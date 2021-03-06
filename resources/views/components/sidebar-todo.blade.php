<div class="h-screen flex flex-col">
    <div class="task__header border-b flex-shrink px-8 py-5 flex justify-between items-center">
        <p class="text-2xl font-semibold">Công việc</p>
        <div class="task__header--button flex gap-3">
            <button class="border-gray-300 border px-3 py-0.5 rounded" type="button"
                    data-modal-toggle="task-modal">
                <i class="text-xl far fa-check-circle"></i>
            </button>
        </div>
    </div>
    <div id="sidebar-todo-content" class="flex-grow flex flex-col">
        <div class="flex-shrink mb-4 border-b flex justify-center border-gray-200 dark:border-gray-700">
            <ul class="flex -mb-px text-sm font-medium text-center" id="myTab" data-tabs-toggle="#tab-todo"
                role="tablist">
                <li class="mr-2" role="presentation">
                    <button class="inline-block p-4 rounded-t-lg border-b-2" id="pending-tab"
                            data-tabs-target="#todo-pending" type="button" role="tab" aria-controls="todo-pending"
                            aria-selected="false">Chưa xong
                    </button>
                </li>
                <li class="mr-2" role="presentation">
                    <button
                        class="inline-block p-4 rounded-t-lg border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
                        id="complete-tab" data-tabs-target="#todo-complete" type="button" role="tab"
                        aria-controls="todo-complete" aria-selected="false">Đã xong
                    </button>
                </li>
                <li class="mr-2" role="presentation">
                    <button
                        class="inline-block p-4 rounded-t-lg border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
                        id="in-complete-tab" data-tabs-target="#todo-in-complete" type="button" role="tab"
                        aria-controls="todo-in-complete" aria-selected="false">Quá hạn
                    </button>
                </li>
            </ul>
        </div>
        <div id="tab-todo">
            <div class="hidden p-4 h-screen rounded-lg" style="height: 86vh" id="todo-pending" role="tabpanel" aria-labelledby="pending-tab">
                <ul id="list-todo-pending" class="h-full border-t overflow-y-auto flex flex-col">

                    @foreach(Auth::user()->tasks->where('status', \App\Enums\TaskStatus::PENDING) as $task)
                        <li class="px-3 border-b py-3">
                            <button data-task-id="{{ $task->id }}" data-task="{{ $task }}"
                                    class="w-full btn-open-task-detail">
                                <div class="flex gap-3">
                                    <img class="w-10 h-10 p-1 rounded-full ring-2 ring-gray-300 dark:ring-gray-500"
                                         src="{{ $task->owner->avatar }}" alt="">
                                    <div class="flex flex-col gap-1">
                                        @if (isset($task->title))
                                            <p class="text-xl todo__title font-semibold">{{ $task->title }}</p>
                                        @else
                                            <p class="text-xl todo__title font-semibold text-gray-400">(Không có tiêu đề)</p>
                                        @endif
                                        <p class="text-sm todo__content text-limit-line text-left">{{ $task->content }}</p>
                                    </div>
                                </div>
                                <div class="flex justify-between mt-1">
                                    <p class="text-sm text-gray-400">Đã nhận</p>
                                    <p class="text-sm todo__due">Thời hạn: {{ $task->due_date ?: 'Không có thời hạn' }}</p>
                                </div>
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="hidden h-screen p-4 rounded-lg" style="height: 86vh" id="todo-complete" role="tabpanel"
                 aria-labelledby="complete-tab">
                <ul id="list-todo-complete" class="h-full border-t overflow-y-auto flex flex-col">
                    @foreach(Auth::user()->tasks->where('status', \App\Enums\TaskStatus::COMPLETED) as $task)
                        <li class="px-3 border-b py-3">
                            <button data-task-id="{{ $task->id }}" data-task="{{ $task }}"
                                    class="w-full btn-open-task-detail">
                                <div class="flex gap-3">
                                    <img class="w-10 h-10 p-1 rounded-full ring-2 ring-gray-300 dark:ring-gray-500"
                                         src="{{ $task->owner->avatar }}" alt="">
                                    <div class="flex flex-col gap-1">
                                        @if (isset($task->title))
                                            <p class="text-xl todo__title font-semibold">{{ $task->title }}</p>
                                        @else
                                            <p class="text-xl todo__title font-semibold text-gray-400">(Không có tiêu đề)</p>
                                        @endif
                                        <p class="text-sm todo__content text-left text-limit-line">{{ $task->content }}</p>
                                    </div>
                                </div>
                                <div class="flex justify-between mt-1">
                                    <p class="text-sm text-gray-400">Đã nhận</p>
                                    <p class="text-sm todo__due">Thời
                                        hạn: {{ $task->due_date ?: 'Không có thời hạn' }}</p>
                                </div>
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="hidden p-4 h-screen rounded-lg" style="height: 86vh;" id="todo-in-complete" role="tabpanel"
                 aria-labelledby="in-complete-tab">
                <ul id="list-todo-in-complete" class="h-full border-t overflow-y-auto flex flex-col">
                    @foreach(Auth::user()->tasks->where('status', \App\Enums\TaskStatus::IN_COMPLETE) as $task)
                        <li class="px-3 border-b py-3">
                            <button data-task-id="{{ $task->id }}" data-task="{{ $task }}"
                                    class="w-full btn-open-task-detail">
                                <div class="flex gap-3">
                                    <img class="w-10 h-10 p-1 rounded-full ring-2 ring-gray-300 dark:ring-gray-500"
                                         src="{{ $task->owner->avatar }}" alt="">
                                    <div class="flex flex-col gap-1">
                                        @if (isset($task->title))
                                            <p class="text-xl todo__title font-semibold">{{ $task->title }}</p>
                                        @else
                                            <p class="text-xl todo__title font-semibold text-gray-400">(Không có tiêu đề)</p>
                                        @endif
                                        <p class="text-sm todo__content text-left text-limit-line">{{ $task->content }}</p>
                                    </div>
                                </div>
                                <div class="flex justify-between mt-1">
                                    <p class="text-sm text-gray-400">Đã nhận</p>
                                    <p class="text-sm text-red-500">Thời
                                        hạn: <span class="todo__due">{{ $task->due_date ?: 'Không có thời hạn' }}</span></p>
                                </div>
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
