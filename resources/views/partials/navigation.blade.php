<link rel="stylesheet" href="{{ asset('css/app.css') }}">
<link rel="stylesheet" href="{{ asset('css/navigation.css') }}">


<div id="navigation">
    <div id="nav" class="flex flex-col justify-between py-5">
        <ul class="flex flex-col items-center gap-8" data-tabs-toggle="#sidebar" role="tablist">
            <li>
                <svg style="fill: #4eac6d" xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                     viewBox="0 0 24 24">
                    <path
                        d="M8.5,18l3.5,4l3.5-4H19c1.103,0,2-0.897,2-2V4c0-1.103-0.897-2-2-2H5C3.897,2,3,2.897,3,4v12c0,1.103,0.897,2,2,2H8.5z M7,7h10v2H7V7z M7,11h7v2H7V11z"></path>
                </svg>
            </li>
            {{--            <li>--}}
            {{--                <button class="button-tab active" data-tooltip-target="button-profile" data-tabs-target="#tab-profile"--}}
            {{--                        type="button" role="tab" aria-controls="profile" aria-selected="false">--}}
            {{--                    <i class="far fa-user-circle icon-nav "></i>--}}
            {{--                </button>--}}
            {{--                <div id="button-profile" role="tooltip"--}}
            {{--                     class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">--}}
            {{--                    Profile--}}
            {{--                    <div class="tooltip-arrow" data-popper-arrow></div>--}}
            {{--                </div>--}}
            {{--            </li>--}}
            <li>
                <button class="button-tab active" data-tooltip-target="button-chats" data-tabs-target="#tab-chat"
                        type="button"
                        role="tab" aria-controls="chats" aria-selected="true">
                    <i class="far fa-comments-alt icon-nav"></i>
                </button>
                <div id="button-chats" role="tooltip"
                     class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                    Chats
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>
            </li>
            <li>
                <button class="button-tab" data-tooltip-target="button-contacts" data-tabs-target="#tab-contact"
                        type="button" role="tab" aria-controls="profile" aria-selected="false">
                    <i class="fal fa-address-card icon-nav"></i>
                </button>
                <div id="button-contacts" role="tooltip"
                     class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                    Contacts
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>
            </li>
            <li>
                <button class="button-tab" data-tooltip-target="button-friend-pending"
                        data-tabs-target="#tab-friend-pending" type="button" role="tab" aria-controls="profile"
                        aria-selected="false">
                    <i class="fal fa-user-alt icon-nav"></i>
                </button>
                <div id="button-friend-pending" role="tooltip"
                     class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                    Friend Pending
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>
            </li>
            <li>
                <button class="button-tab" data-tooltip-target="button-settings" data-tabs-target="#tab-setting"
                        type="button" role="tab" aria-controls="profile" aria-selected="false">
                    <i class="far fa-cog icon-nav"></i>
                </button>
                <div id="button-settings" role="tooltip"
                     class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                    Settings
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>
            </li>
        </ul>
        <ul class="flex flex-col items-center">
            <li>
                <button data-dropdown-toggle="dropdown_user" data-dropdown-placement="top">
                    <img class="w-10 h-10 p-1 rounded-full ring-2 ring-gray-300 dark:ring-gray-500"
                         src="{{ Auth::user()->profile->avatar }}" alt="Bordered avatar">
                </button>
                <div id="dropdown_user"
                     class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700">
                    <ul class="py-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownTopButton">
                        <li>
                            <a href="#"
                               class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white font-semibold">Profile</a>
                        </li>
                        <li>
                            <a href="#"
                               class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white font-semibold">Settings</a>
                        </li>
                        <li>
                            <a href="{{ url('/auth/logout') }}"
                               class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white font-semibold">Sign
                                out</a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
    <div id="sidebar">
        {{--        <div id="tab-profile" class="hidden" role="tabpanel" aria-labelledby="profile-tab">--}}
        {{--            <div class="profile_header">--}}
        {{--                <p class="text-xl font-semibold">My Profile</p>--}}
        {{--                <div id="profile_banner">--}}
        {{--                </div>--}}
        {{--            </div>--}}
        {{--        </div>--}}
        <div id="tab-chat" class="hidden">
            <div class="tab_header">
                <div class="flex justify-between items-center">
                    <p class="text-xl font-semibold">Chats</p>
                    <button type="button"
                            class="focus:outline-none border-0 text-white bg-green-300 hover:bg-green-500 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center dark:bg-green-500 dark:hover:bg-green-500 dark:focus:ring-green-500">
                        <i class="far fa-plus"></i>
                    </button>
                </div>
                <div class="tab-search mt-3">
                    <input type="text"
                           placeholder="Search here..."
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-green-500 dark:focus:border-green-500"
                    >
                </div>
                <div class="flex flex-col gap-5 chat-list py-3">
                    <button class="chat-item flex items-center gap-3">
                        <img class="w-8 h-8 rounded-full" src="{{ Auth::user()->profile->avatar }}"
                             alt="Rounded avatar">
                        <p class="font-semibold text-md">{{ Auth::user()->profile->full_name }}</p>
                    </button>
                    <button class="chat-item flex items-center gap-3">
                        <img class="w-8 h-8 rounded-full" src="{{ Auth::user()->profile->avatar }}"
                             alt="Rounded avatar">
                        <p class="font-semibold text-md">{{ Auth::user()->profile->full_name }}</p>
                    </button>
                    <button class="chat-item flex items-center gap-3">
                        <img class="w-8 h-8 rounded-full" src="{{ Auth::user()->profile->avatar }}"
                             alt="Rounded avatar">
                        <p class="font-semibold text-md">{{ Auth::user()->profile->full_name }}</p>
                    </button>
                </div>
            </div>
        </div>
        <div id="tab-friend-pending" class="hidden">
            <div class="tab_header">
                <div class="flex justify-between items-center">
                    <p class="text-xl font-semibold">Friend Pending</p>
                    <button type="button"
                            class="focus:outline-none border-0 text-white bg-green-300 hover:bg-green-500 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center dark:bg-green-500 dark:hover:bg-green-500 dark:focus:ring-green-500">
                        <i class="far fa-plus"></i>
                    </button>
                </div>
                <div class="tab-search mt-3">
                    <input type="text"
                           placeholder="Search here..."
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-green-500 dark:focus:border-green-500"
                    >
                </div>
                <div class="flex flex-col gap-5 contact-list py-3">
                    <button class="contact-item flex items-center gap-3">
                        <img class="w-8 h-8 rounded-full" src="{{ Auth::user()->profile->avatar }}"
                             alt="Rounded avatar">
                        <p class="font-semibold text-md">{{ Auth::user()->profile->full_name }}</p>
                    </button>
                    <button class="contact-item flex items-center gap-3">
                        <img class="w-8 h-8 rounded-full" src="{{ Auth::user()->profile->avatar }}"
                             alt="Rounded avatar">
                        <p class="font-semibold text-md">{{ Auth::user()->profile->full_name }}</p>
                    </button>
                    <button class="contact-item flex items-center gap-3">
                        <img class="w-8 h-8 rounded-full" src="{{ Auth::user()->profile->avatar }}"
                             alt="Rounded avatar">
                        <p class="font-semibold text-md">{{ Auth::user()->profile->full_name }}</p>
                    </button>
                </div>
            </div>
        </div>
        <div id="tab-contact" class="hidden">
            <div class="tab_header">
                <div class="flex justify-between items-center">
                    <p class="text-xl font-semibold">Contacts</p>
                    <button type="button"
                            data-modal-toggle="modal_add_friend"
                            class="focus:outline-none border-0 text-white bg-green-300 hover:bg-green-500 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center dark:bg-green-500 dark:hover:bg-green-500 dark:focus:ring-green-500">
                        <i class="far fa-plus"></i>
                    </button>

                </div>
                <div class="tab-search mt-3">
                    <input type="text"
                           placeholder="Search here..."
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-green-500 dark:focus:border-green-500"
                    >
                </div>
                <div class="flex flex-col gap-5 contact-list py-3">
                    <button class="contact-item flex items-center gap-3">
                        <img class="w-8 h-8 rounded-full" src="{{ Auth::user()->profile->avatar }}"
                             alt="Rounded avatar">
                        <p class="font-semibold text-md">{{ Auth::user()->profile->full_name }}</p>
                    </button>
                    <button class="contact-item flex items-center gap-3">
                        <img class="w-8 h-8 rounded-full" src="{{ Auth::user()->profile->avatar }}"
                             alt="Rounded avatar">
                        <p class="font-semibold text-md">{{ Auth::user()->profile->full_name }}</p>
                    </button>
                    <button class="contact-item flex items-center gap-3">
                        <img class="w-8 h-8 rounded-full" src="{{ Auth::user()->profile->avatar }}"
                             alt="Rounded avatar">
                        <p class="font-semibold text-md">{{ Auth::user()->profile->full_name }}</p>
                    </button>
                </div>
            </div>
        </div>
        <div id="tab-setting" class="hidden">
            <div class="profile_header">
                Setting
            </div>
        </div>
    </div>
</div>

<div id="modal_add_friend" tabindex="-1" aria-hidden="true"
     class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full">
    <div class="relative p-4 w-full max-w-md h-full md:h-auto">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <div class="flex justify-end p-2">
                <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white"
                        data-modal-toggle="modal_add_friend">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                         xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                              d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                              clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
            <form class="px-6 pb-4 space-y-6 lg:px-8 sm:pb-6 xl:pb-8" action="#">
                <h3 class="text-xl font-medium text-gray-900 dark:text-white">Add Contact</h3>
                <div>
                    <label for="email"
                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Email</label>
                    <input type="email" name="email" id="email"
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                           placeholder="name@company.com" required>
                </div>
                <button type="submit"
                        class="w-full text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                    Invite
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    const tabs = Array.from(document.querySelectorAll('.button-tab'));
    tabs.forEach((button) => {
        button.addEventListener('click', async () => {
            await tabs.forEach((tab) => {
                tab.classList.remove('active');
            });
            button.classList.add('active');
        })
    })
</script>