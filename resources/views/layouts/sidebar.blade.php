<div class="sidebar" data-color="white" data-active-color="danger">
    <div class="logo">
        <a href="/" class="simple-text logo-mini">
            <div class="logo-image-small">
                <img src="/img/logo-small.png" alt="brushamania-logo">
            </div>
        </a>
        <a href="/" class="simple-text logo-normal">
            BRUSH-A-MANIA
        </a>
    </div>
    <div class="sidebar-wrapper">
        <ul class="nav">
            @if ($role == 'admin')
                <sidebar-component v-for="item in admin_items" :item="item"
                    :key="item.name"></sidebar-component>
            @elseif($role == 'parent')
                <sidebar-component v-for="item in parent_items" :item="item"
                    :key="item.name"></sidebar-component>
            @elseif($role == 'school')
                <sidebar-component v-for="item in school_items" :item="item"
                    :key="item.name"></sidebar-component>
            @elseif($role == 'dentist')
                <sidebar-component v-for="item in dentist_items" :item="item"
                    :key="item.name"></sidebar-component>
            @elseif($role == 'rotarian')
                <sidebar-component v-for="item in rotarian_items" :item="item"
                    :key="item.name"></sidebar-component>
            @elseif($role == 'student')
                <sidebar-component v-for="item in student_items" :item="item"
                    :key="item.name"></sidebar-component>
            @endif
            <li>
                <a href="/logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                        class="nc-icon nc-button-power"></i>Logout</a>
            </li>
            <form id="logout-form" action="/logout" method="POST" style="display: none;">
                @csrf
            </form>
        </ul>
    </div>
</div>
