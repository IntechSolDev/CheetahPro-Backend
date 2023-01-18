<!-- Sidebar -->
<ul class="sidebar navbar-nav">
    <li class="nav-item active">
        <a class="nav-link" href="{{url('/admin/')}}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>
      <li class="nav-item">
        <a class="nav-link" href="{{route('user.index')}}">
            <i class="fas fa-fw fa-users"></i>
            <span> User</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{route('provider.index')}}">
            <i class="fas fa-fw fa-users"></i>
            <span> Service Provider</span></a>
    </li>

 <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
           <i class="fas fa-fw fa-toolbox"></i>
        <span>Service</span>
     </a>
     <div class="dropdown-menu" aria-labelledby="pagesDropdown">
   <a class="dropdown-item " href="{{route('service.index')}}">
             <i class="fas fa-fw fa-tasks"></i>
         <span>Main Service</span></a>

      <a class="dropdown-item " href="{{route('sub-service.index')}}">
               <i class="fas fa-fw fa-wrench"></i>
               <span>Sub Sevice</span></a>
        </div>
</li>

    <li class="nav-item">
        <a class="nav-link" href="{{route('order.index')}}">
            <i class="fas fa-fw fa-book"></i>
            <span> Booking </span></a>
    </li>

    <li class="nav-item" style="display:none;">
        <a class="nav-link" href="{{route('order.index')}}">
            <i class="fas fa-fw fa-shopping-cart"></i>
            <span> Order </span></a>
    </li>
    
       <li class="nav-item">
        <a class="nav-link" href="{{route('subscription.index')}}">
            <i class="fas fa-fw fa-money-bill"></i>
            <span> Subsciption </span></a>
    </li>

</ul>
