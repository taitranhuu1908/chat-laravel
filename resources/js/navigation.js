import Swal from 'sweetalert2';
import Toastify from 'toastify-js'


$(() => {
    const userId = document.getElementById('user_id').value;
    const Echo = window.Echo;
    const axios = window.axios;
    const buttonTabs = Array.from(document.querySelectorAll('.nav__top--button'));
    let listFriendCreateRoom = [];

    rooms.forEach(room => {
        Echo.channel('chat-room.' + room.id).listen('ChatEvent', (e) => {
            const indicator = $('#nav__chat .indicator__dot');
            if (indicator.hasClass('hidden')) {
                indicator.removeClass('hidden');
            }
        });
    })



    Echo.channel(`add-friend.${userId}`).listen('AddFriendEvent', (data) => {
        $('#list-request-friend').append(renderFriendRequest(data.friend.avatar, data.friend.full_name, data.friend.id, data.description));
        Toastify({
            text: `${data.friend.full_name} đã gửi lời mời kết bạn`,
            duration: 3000,
            newWindow: true,
            close: true,
            gravity: "top",
            position: "right",
            className: 'toastify-info'
        }).showToast();
        addEvent();

        const indicator = $('#nav__request .indicator__dot');
        if (indicator.hasClass('hidden')) {
            indicator.removeClass('hidden');
        }
    })

    Echo.channel(`accept-friend.${userId}`).listen('AcceptFriendEvent', (data) => {
        $('#sidebar_friend_list')
            .append(renderFriendItem(
                data.friend.avatar, data.friend.full_name, data.friend.id, `Xin chào ${data.friend.full_name}`));
        Toastify({
            text: `Bạn và ${data.friend.full_name} đã trở thành bạn bè`,
            duration: 3000,
            newWindow: true,
            close: true,
            gravity: "top",
            position: "right",
            className: 'toastify-info'
        }).showToast();
        const indicator = $('#nav__friend .indicator__dot');
        if (indicator.hasClass('hidden')) {
            indicator.removeClass('hidden');
        }
        addEvent();
    })

    Echo.channel(`create-room.${userId}`).listen('CreateRoomEvent', (data) => {
        $('#chat_rooms').append(renderChatRoom(data.room));
        if (data.room.room_type === 'GROUP_ROOM') {
            Toastify({
                text: `Ban được mời vào phòng chat ${data.room.name}`,
                duration: 3000,
                newWindow: true,
                close: true,
                gravity: "top",
                position: "right",
                className: 'toastify-info'
            }).showToast();
        }
        addEvent();
    });

    // Change Tab Active
    buttonTabs.forEach(button => {
        button.addEventListener('click', function() {
            buttonTabs.forEach(btn => {
                btn.classList.remove('nav__top--active');
            })
            button.classList.add('nav__top--active');
            const parent = $(this).parent();
            const indicator = parent.children('.indicator__dot');
            if (!indicator.hasClass('hidden')) {
                indicator.addClass('hidden');
            }

        })
    })
    addEvent();

    document.getElementById('search_sidebar_chat').addEventListener('keyup', (e) => {
        const list = Array.from($('#chat_rooms').children());
        const keyword = e.target.value;

        list.forEach(function(item) {
            const name = item.getElementsByClassName('chat__room--name')[0].innerText;
            if (name.toLowerCase().indexOf(keyword.toLowerCase()) === -1) {
                item.classList.add('hidden');
            } else {
                item.classList.remove('hidden');
            }
        })
    })

    // Filter Friend
    $('#search-add-friend').autocomplete({
        source: function (request, response) {
            response($.map(friends, function (item, index) {

                const fullName = item.user.full_name;
                const email = item.user.email;

                if (fullName.toLowerCase().indexOf(request.term.toLowerCase()) > -1 || email.toLowerCase().indexOf(request.term.toLowerCase()) > -1) {
                    return {
                        label: `${item.user.email} (${item.user.full_name})`,
                        value: item.user.email
                    }
                } else {
                    return null;
                }
            }))
        }
    }, {});

    // Add Friend To Room
    $('#btn-add-friend-to-room').click(function (e) {
        e.preventDefault();

        const email = $('#search-add-friend');
        const friendObj = friends.filter(item => item.user.email === email.val())[0];

        if (friendObj) {
            listFriendCreateRoom.push(friendObj.user.id);


            if (listFriendCreateRoom.length < 4) {
                const html = `
                    <img data-user-id=${friendObj.user.id} class="avatar-user-to-room cursor-pointer w-10 h-10 border-2 border-white rounded-full dark:border-gray-800"
                        src="${friendObj.user.avatar}" alt="">
                 `;
                $('#list-avatar-user-to-room').prepend(html);
            }

            $('#list-user-in-room-count').text(listFriendCreateRoom.length);
            email.val('');
            const avatarUser = $('.avatar-user-to-room');
            avatarUser.unbind('click');
            avatarUser.click(function (e) {
                const userId = $(this).attr('data-user-id');
                $(this).remove();
                listFriendCreateRoom = listFriendCreateRoom.filter(item => item != userId);
                $('#list-user-in-room-count').text(listFriendCreateRoom.length);
            })
        }
    })


    $('#btn-create-group').click(function (e) {
        e.preventDefault();

        const roomName = $('#group_name');
        const description = $('#message_group');

        if (!roomName.val()) {
            Toastify({
                text: 'Tên nhóm không được để trống',
                duration: 3000,
                newWindow: true,
                close: true,
                gravity: "top",
                position: "right",
                className: 'toastify-warning'
            }).showToast();
            roomName.focus();
            return;
        }

        if (listFriendCreateRoom.length === 0) {
            Toastify({
                text: 'Nhóm phải có ít nhất 1 thành viên',
                duration: 3000,
                newWindow: true,
                close: true,
                gravity: "top",
                position: "right",
                className: 'toastify-warning'
            }).showToast();
            $('#search-add-friend').focus();
            return;
        }

        axios.post('/room/create-room-group', {
            name: roomName.val(),
            description: description.val(),
            members: listFriendCreateRoom
        }).then((response) => {
            roomName.val('');
            description.val('');
            listFriendCreateRoom = [];
            $('#chat_rooms').prepend(renderChatRoom(response.data.data))
            const wrapperListAvatar = $('#list-avatar-user-to-room');
            wrapperListAvatar.empty();
            wrapperListAvatar.append(`
                <a id="list-user-in-room-count"
                   class="flex items-center justify-center w-10 h-10 text-xs font-medium text-white bg-gray-700 border-2 border-white rounded-full hover:bg-gray-600 dark:border-gray-800"
                   href="#">+0</a>
            `)
            Toastify({
                text: 'Tạo nhóm thành công',
                duration: 3000,
                newWindow: true,
                close: true,
                gravity: "top",
                position: "right",
                className: 'toastify-success'
            }).showToast();
        }).catch((error) => {
            console.log(error)
        })
    })

    // Submit form add friend
    $('#form-add-friend').submit((e) => {
        e.preventDefault();
        const email = $('#email-add-friend');
        const description = $('#description-add-friend');
        axios.post('/user/add-friend-request', {
            email: email.val(),
            description: description.val()
        }, {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'X-Requested-With': 'XMLHttpRequest',
        }).then((response) => {
            email.val('');
            description.val('');
            Toastify({
                text: `Đã gửi lời mời kết bạn`,
                duration: 3000,
                newWindow: true,
                close: true,
                gravity: "top",
                position: "right",
                className: 'toastify-success'
            }).showToast();
        }).catch((error) => {
            Toastify({
                text: error.response.data.message,
                duration: 3000,
                newWindow: true,
                close: true,
                gravity: "top",
                position: "right",
                className: 'toastify-error'
            }).showToast();
            email.val('');
            description.val('');
        })
    })

    function renderChatRoom(room) {
        let roomName = '';
        let avatar = '';

        if (room.room_type === 'PRIVATE_ROOM') {
            room.users.forEach(item => {
                if (item.user_id !== userId) {
                    roomName = item.full_name;
                    avatar = item.avatar;
                }
            })
        } else if (room.room_type === 'GROUP_ROOM') {
            roomName = room.name;
            avatar = room.image ? room.image : '/images/default-avatar.png';
        }

        return `
        <li data-room-id="${room.id}" class="room rooms__item border-b py-3 w-full px-8 flex items-center">
            <a href="/room/${room.id}" class="block w-full">
                <div class="flex overflow-hidden items-center w-full gap-3">
                    <img class="w-10 h-10 rounded-full" src="${avatar}" alt="Rounded avatar">
                    <div class="w-full overflow-hidden">
                        <p
                            class="chat__room--name text-lg overflow-hidden whitespace-nowrap w-2/4 text-ellipsis text-blue-600 font-semibold">
                            ${roomName}
                        </p>
                        <p class="text-md overflow-hidden whitespace-nowrap w-2/4 text-ellipsis">
                            No messages yet
                        </p>
                    </div>
                </div>
            </a>
            <button class="button-friend-request" data-dropdown-placement="right">
                <i class="fas fa-ellipsis-h-alt"></i>
                <div
                    class="hidden absolute z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dropdown-friend-request">
                    <ul class="text-left py-1 w-full text-sm text-gray-700 dark:text-gray-200"
                        aria-labelledby="dropdownRightButton">
                        <li>
                            <a href='/room/${room.id}'
                            class=" block text-md font-semibold py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Mở chat</a>
                        </li>
                        <li>
                            <a data-user-id="${room.id}" href="#"
                            class=" block text-md font-semibold py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                            ${room.room_type === 'PRIVATE_ROOM' ? 'Chặn' : 'Rời phòng'}
                            </a>
                        </li>
                    </ul>
                </div>
            </button>
        </li>
        `
    }

    function renderFriendRequest(avatar, full_name, id, message) {
        return `
        <li class="rooms__item border-b py-3 w-full px-8 flex items-center">
        <div class="flex overflow-hidden items-center w-full gap-3">
            <img class="w-10 h-10 rounded-full" src="${avatar}" alt="Rounded avatar">
            <div class="w-full overflow-hidden">
                <p
                    class="text-lg overflow-hidden whitespace-nowrap w-2/4 text-ellipsis text-blue-600 font-semibold">
                    ${full_name}</p>
                <p class="text-md overflow-hidden whitespace-nowrap w-2/4 text-ellipsis">${message}</p>
            </div>
        </div>
        <button class="button-friend-request" data-dropdown-placement="right">
            <i class="fas fa-ellipsis-h-alt"></i>
            <div
                class="hidden absolute z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dropdown-friend-request">
                <ul class="text-left py-1 w-full text-sm text-gray-700 dark:text-gray-200"
                    aria-labelledby="dropdownRightButton">
                    <li>
                        <a data-user-id="${id}" href="#"
                            class="accept-friend-request block text-md font-semibold py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Chấp nhận</a>
                    </li>
                    <li>
                        <a data-user-id="${id}" href="#"
                            class="block-friend-request block text-md font-semibold py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Huỷ bỏ</a>
                    </li>
                </ul>
            </div>
        </button>
    </li>
        `
    }

    function renderFriendItem(avatar, full_name, id, message) {
        return `
            <li class="rooms__item border-b py-3 w-full px-8 flex items-center">
                <div class="flex overflow-hidden items-center w-full gap-3">
                    <img class="w-10 h-10 rounded-full" src="${avatar}" alt="Rounded avatar">
                    <div class="w-full overflow-hidden">
                        <p
                            class="text-lg overflow-hidden whitespace-nowrap w-2/4 text-ellipsis text-blue-600 font-semibold">
                            ${full_name}</p>
                        <p class="text-md overflow-hidden whitespace-nowrap w-2/4 text-ellipsis">
                           ${message}
                        </p>
                    </div>
                </div>
                <button class="button-friend-request" data-dropdown-placement="right">
                    <i class="fas fa-ellipsis-h-alt"></i>
                    <div
                        class="hidden absolute z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dropdown-friend-request">
                        <ul class="text-left py-1 w-full text-sm text-gray-700 dark:text-gray-200"
                            aria-labelledby="dropdownRightButton">
                            <li>
                                <a data-user-id="${id}" href="#"
                                   class="btn-create-private block text-md font-semibold py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Mở chat</a>
                            </li>
                            <li>
                                <a data-user-id="${id}" href="#"
                                   class="block text-md font-semibold py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Xem hồ sơ</a>
                            </li>
                            <li>
                                <a data-user-id="${id}" href="#"
                                   class="btn-block-friend block text-md font-semibold py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Huỷ kết bạn</a>
                            </li>
                        </ul>
                    </div>
                </button>
            </li>
        `
    }

    function addEvent() {
        const buttonDropdown = $('.button-friend-request i');
        const buttonAcceptFriendRequest = $('.accept-friend-request');
        const buttonBlockFriendRequest = $('.block-friend-request');
        const buttonBlockFriend = $('.btn-block-friend');
        const buttonCreateRoomPrivate = $('.btn-create-private');
        const buttonLeaveGroup = $('.btn-leave-group');

        buttonDropdown.unbind('click');
        buttonAcceptFriendRequest.unbind('click');
        buttonBlockFriendRequest.unbind('click');
        buttonBlockFriend.unbind('click');
        buttonCreateRoomPrivate.unbind('click');
        buttonLeaveGroup.unbind('click');

        buttonLeaveGroup.click(function (e) {
            e.preventDefault();
            const roomId = $(this).attr('data-room-id');
            const parents = $(this).parents('.rooms__item');


            axios.post('/room/leave-group', {
                roomId: roomId
            }).then(res => {
                if (res.data) {
                    parents.remove();
                    Toastify({
                        text: 'Đã rời khỏi nhóm',
                        duration: 3000,
                        className: 'toastify-success',
                    }).showToast();
                }
            }).catch(err => {
                console.log(err)
            })
        });

        buttonDropdown.click(function (e) {
            e.preventDefault();
            const button = $(this).parent();
            const parent = button.children('.dropdown-friend-request');
            parent.toggleClass('hidden')

            $(document).click(function (e) {
                if (!button.is(e.target) && button.has(e.target).length === 0) {
                    parent.addClass('hidden')
                }
            })
        })

        buttonAcceptFriendRequest.click(function (e) {
            e.preventDefault();
            const id = $(this).attr('data-user-id');

            const parent = $(this).parent().parent().parent().parent().parent();

            Swal.fire({
                title: 'Bạn có muốn chấp nhận lời mời kết bạn ?',
                showCancelButton: true,
                confirmButtonText: 'Ok',
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    axios.post('/user/accept-friend-request', {
                        user_accept_id: id
                    }).then((response) => {
                        if (response.data.status === 200) {
                            parent.remove();
                            Toastify({
                                text: `Bạn đã chấp nhận lời mời kết bạn!`,
                                duration: 3000,
                                close: true,
                                gravity: "top",
                                position: "right",
                                className: 'toastify-success'
                            }).showToast();
                        }
                    }).catch((error) => {
                        Toastify({
                            text: `${error.response.data.message}`,
                            duration: 3000,
                            close: true,
                            gravity: "top",
                            position: "right",
                            className: 'toastify-error'
                        }).showToast();
                    })
                }
            })


        })

        buttonBlockFriendRequest.click(function (e) {
            e.preventDefault();
            const id = $(this).attr('data-user-id');

            const parent = $(this).parent().parent().parent().parent().parent();

            Swal.fire({
                title: 'Bạn muốn huỷ lời mời kết bạn?',
                showCancelButton: true,
                confirmButtonText: 'Ok',
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post('/user/block-friend-request', {
                        user_block_id: id
                    }).then((response) => {
                        if (response.data.status === 200) {
                            parent.remove();
                        }
                        Toastify({
                            text: `Bạn đã huỷ lời mời kết bạn!`,
                            duration: 3000,
                            close: true,
                            gravity: "top",
                            position: "right",
                            className: 'toastify-success'
                        }).showToast();
                    }).catch((error) => {
                        Toastify({
                            text: `${error.response.data.message}`,
                            duration: 3000,
                            close: true,
                            gravity: "top",
                            position: "right",
                            className: 'toastify-error'
                        }).showToast();
                    })
                }
            })
        })

        buttonBlockFriend.click(function (e) {
            e.preventDefault();
            const id = $(this).attr('data-user-id');

            const parent = $(this).parent().parent().parent().parent().parent();

            Swal.fire({
                title: 'You want to unfriend?',
                showCancelButton: true,
                confirmButtonText: 'Ok',
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post('/user/block-friend', {
                        user_block_id: id
                    }).then((response) => {
                        if (response.data.status === 200) {
                            parent.remove();
                            Toastify({
                                text: `Bạn đã huỷ kết bạn thành công!`,
                                duration: 3000,
                                close: true,
                                gravity: "top",
                                position: "right",
                                className: 'toastify-success'
                            }).showToast();
                        }
                    }).catch((error) => {
                        Toastify({
                            text: `${error.response.data.message}`,
                            duration: 3000,
                            close: true,
                            gravity: "top",
                            position: "right",
                            className: 'toastify-error'
                        }).showToast();
                    })
                }
            })
        })

        buttonCreateRoomPrivate.click(function (e) {
            e.preventDefault();
            const id = $(this).attr('data-user-id');

            axios.post('/room/create-room-private', {
                user_id: id
            }).then((response) => {
                if (response.data.status === 200) {
                    window.location.href = '/room/' + response.data.data.id;
                }
            }).catch((error) => {
                if (error.response.data.status === 409) {
                    window.location.href = '/room/' + error.response.data.data.id;
                }
            })

            $(document).click(function (e) {
                if (!buttonCreateRoomPrivate.is(e.target) && buttonCreateRoomPrivate.has(e.target).length === 0) {
                    parent.addClass('hidden')
                }
            })
        })
    }

    $('#user_avatar').change((e) => {
        const file = e.target.files[0];
        const fr = new FileReader();
        fr.readAsDataURL(file);
        fr.onload = (e) => {
            $('#user_avatar_preview').attr('src', e.target.result);
        }
    })

    $('#form_edit_profile').submit(function (e) {
        e.preventDefault();
        const full_name = $('#txt_full_name').val();
        const email = $('#txt_email').val();
        const avatar = $('#user_avatar_preview').attr('src');
        const phone = $('#txt_phone').val();
        const address = $('#txt_address').val();
        const country = $('#txt_country').val();
        const about_me = $('#about_myself').val();

        const data = {
            full_name: full_name,
            email: email,
            avatar: avatar,
            phone: phone,
            address: address,
            country: country,
            about_myself: about_me
        }
        axios.put('/user/edit-profile', data)
            .then((response) => {
                if (response.data.status === 200) {
                    $('#avatar_user_navigation').attr('src', avatar);
                    Toastify({
                        text: `Chỉnh sửa hồ sơ thành công!`,
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        className: 'toastify-success'
                    }).showToast();
                }
            })
            .catch((error) => {
                Toastify({
                    text: `${error.response.data.message}`,
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    className: 'toastify-error'
                }).showToast();
            })
    })
})

