<div class="sidebar" data-color="purple" data-background-color="white" data-image="theme/assets/img/sidebar-1.jpg">
    <div class="logo">
        <a href="{{ url('/') }}" class="simple-text logo-normal">
            Pavel.One CRM
        </a>
    </div>
    <div class="sidebar-wrapper">
        <div class="user">
            <div class="avatar">
                <img src="{{ getUserPhoto(Auth::user()->id) }}" alt="">
            </div>
            <div class="office">{{ Auth::user()->sudo ? 'Бог' : 'Разработчик' }}</div>
            <div class="username">
                {{ Auth::user()->name }}
            </div>
            <a href="{{ route('Profile') }}" style="display: block;max-width: 70%;margin: 10px auto;" class="btn btn-default">Мой профиль</a>
        </div>
        <ul class="nav">
            <li class="nav-item active  ">
                <a class="nav-link" href="#0">
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

            <li class="nav-item">
                <a class="nav-link" href="{{ route('Users') }}">
                    <i class="fas fa-user-shield"></i>
                    <p>Пользователи</p>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('About') }}">
                    <i class="fas fa-journal-whills"></i>
                    <p>Общая информация</p>
                </a>
            </li>
        </ul>
    </div>
</div>