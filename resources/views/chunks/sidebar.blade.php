<div class="sidebar" data-color="purple" data-background-color="white" data-image="theme/assets/img/sidebar-1.jpg">
    <div class="logo">
        <a href="{{ url('/') }}" class="simple-text logo-normal">
            SK Groups CRM <br>
        </a>
    </div>
    @php
        /** @var User $auth */
        $auth = Auth::user();
        $countMessages = $auth->unreadMessages()->count();
    @endphp
    <div class="sidebar-wrapper">
        <div class="user">
            <div class="avatar">
                <img src="{{ getUserPhoto($auth->id) }}" alt="">
            </div>
            <div class="office">{{ $auth->sudo ? 'Бог' : 'Разработчик' }}</div>
            <div class="username">
                {{ $auth->name }}
            </div>
            <a href="{{ route('Profile') }}" style="display: block;max-width: 70%;margin: 10px auto;"
               class="btn btn-default"
               data-counturl="{{ route('checkNew') }}"
            >
                Мой профиль
                @if ($countMessages)
                    <span>({{$countMessages}})</span>
                @endif
            </a>
        </div>
        <ul class="nav">
            <li class="nav-item
            @if ($route == 'crm')
                    active
                    @elseif ($route == 'CrmPage')
                    active
            @endif
                    ">
                <a class="nav-link" href="/">
                    <i class="fab fa-laravel"></i>
                    <p>Главная</p>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#0">
                    <i class="fas fa-tasks"></i>
                    <p>Мои задачи <b>(3)</b></p>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#0">
                    <i class="fas fa-users"></i>
                    <p>Мои клиенты <b>(13)</b></p>
                </a>
            </li>

            <li class="nav-item
            @if ($route == 'Users')
                    active
@endif
                    ">
                <a class="nav-link" href="{{ route('Users') }}">
                    <i class="fas fa-user-shield"></i>
                    <p>Пользователи</p>
                </a>
            </li>
            <li class="nav-item
                @if ($route == 'About')
                    active
@endif
                    ">
                <a class="nav-link" href="{{ route('About') }}">
                    <i class="fas fa-journal-whills"></i>
                    <p>Общая информация</p>
                </a>
            </li>
        </ul>
    </div>
</div>