<template>
    <div>


        <ul class="nav navbar-top-links navbar-right">



            <!-- /.dropdown -->
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                </a>
                <ul class="dropdown-menu dropdown-user">

                    <li><router-link to="/settings"><i class="fa fa-gear fa-fw"></i> Settings</router-link> 
            </li>
            <li class="divider"></li>
            <li>

                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-sign-out fa-fw"></i>
                    Logout
                </a>

                <form id="logout-form" action="logout" method="POST" style="display: none;">
                    <input type="hidden" name="_token" :value="token">

                </form>



            </li>
            <li class="divider"></li>
            <li style="text-align: center;">{{UserName}}</li>

        </ul>
        <!-- /.dropdown-user -->
        </li>
        <!-- /.dropdown -->
        </ul>



        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav navbar-collapse">
                <ul class="nav" id="side-menu">

                    <li :class="$route.meta.home ? 'active' : ''">
                    <router-link to="/home"><i class="fa fa-envelope fa-fw"></i> Messaging</router-link>
                    </li>

                    <li :class="$route.meta.campaigns ? 'active' : ''">
                    <router-link to="/campaigns"><i class="fa fa-bullhorn fa-fw"></i> Campaigns</router-link>
                    </li>



                    <li :class="$route.meta.contact ? 'active' : ''">
                    <router-link to="/contacts"><i class="fa fa-phone fa-fw"></i> Contacts</router-link>
                    </li>

                    <li :class="$route.meta.messaging ? 'active' : ''">
                    <router-link to="/templates"><i class="fa fa-file-o fa-fw"></i> Templates</router-link>
                    </li>



                </ul>
            </div>
            <!-- /.sidebar-collapse -->
        </div>
        <!-- /.navbar-static-side -->
    </div>

</template>

<script>
    export default {
        props: ['token'],
        created() {
            this.UserName = userName;
        }
    }
</script>
