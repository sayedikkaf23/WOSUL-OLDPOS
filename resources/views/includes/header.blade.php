<!-- Page Loader End -->
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{env('GOOGLE_TAG_ID')??''}}"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
 <header>

     <div class="container">
         <div class="navbar-header-topbar">
             <div class="row justify-content-between align-items-center">
                 <div class="col-lg-auto">
                     <div class="row align-items-center justify-content-between">
                         <div class="col-auto">
                             <div class="multi-language">
                                 <select class="customSelect switch-language">
                                     <option @if (App::getLocale() == 'en') selected @endif value="en">English
                                     </option>
                                     <option @if (App::getLocale() == 'ar') selected @endif value="ar"> عربي</option>
                                 </select>
                             </div>
                         </div>
                         <div class="col-auto">
                             <ul class="info-list">
                                 <li style="direction: ltr;">
                                     <a href="tel:{{ $support_contact_number }}"><svg width="18" height="20" viewBox="0 0 18 20"
                                             fill="none" xmlns="http://www.w3.org/2000/svg">
                                             <path
                                                 d="M4.905 4.32271C4.95 5.01801 5.0625 5.69767 5.2425 6.34609L4.3425 7.28357C4.035 6.34609 3.84 5.35393 3.7725 4.32271H4.905ZM12.3 13.7131C12.9375 13.9006 13.59 14.0178 14.25 14.0646V15.2287C13.26 15.1584 12.3075 14.9552 11.4 14.6427L12.3 13.7131ZM5.625 2.76025H3C2.5875 2.76025 2.25 3.11181 2.25 3.54148C2.25 10.8772 7.9575 16.8224 15 16.8224C15.4125 16.8224 15.75 16.4708 15.75 16.0411V13.3147C15.75 12.885 15.4125 12.5334 15 12.5334C14.07 12.5334 13.1625 12.3772 12.3225 12.0881C12.2475 12.0569 12.165 12.0491 12.09 12.0491C11.895 12.0491 11.7075 12.1272 11.5575 12.2756L9.9075 13.9943C7.785 12.8615 6.045 11.0569 4.965 8.84603L6.615 7.12732C6.825 6.90858 6.885 6.6039 6.8025 6.33047C6.525 5.45549 6.375 4.51802 6.375 3.54148C6.375 3.11181 6.0375 2.76025 5.625 2.76025Z"
                                                 fill="currentColor" />
                                         </svg>
                                         {{ $support_contact_number }}</a>
                                 </li>
                                 <li>
                                     <a href="mailto:support@wosul.sa"><svg width="18" height="20" viewBox="0 0 18 20"
                                             fill="none" xmlns="http://www.w3.org/2000/svg">
                                             <g clip-path="url(#clip0_596_2189)">
                                                 <path
                                                     d="M16.5833 5.88483C16.5833 5.07322 15.9458 4.40918 15.1666 4.40918H3.83329C3.05413 4.40918 2.41663 5.07322 2.41663 5.88483V14.7388C2.41663 15.5504 3.05413 16.2144 3.83329 16.2144H15.1666C15.9458 16.2144 16.5833 15.5504 16.5833 14.7388V5.88483ZM15.1666 5.88483L9.49996 9.57397L3.83329 5.88483H15.1666ZM15.1666 14.7388H3.83329V7.36049L9.49996 11.0496L15.1666 7.36049V14.7388Z"
                                                     fill="currentColor" />
                                             </g>
                                             <defs>
                                                 <clipPath id="clip0_596_2189">
                                                     <rect width="18" height="18.7495" fill="white"
                                                         transform="translate(0 0.416504)" />
                                                 </clipPath>
                                             </defs>
                                         </svg> {{ $support_email }}
                                     </a>
                                 </li>
                             </ul>
                         </div>
                     </div>
                 </div>
                 <div class="col-auto d-none d-lg-block">
                     <div class="row align-items-center">
                         <div class="col">
                             <ul class="info-list">
                                 <li>
                                     <a @if (App::getLocale() == 'ar') style="width: 77px;" @endif
                                         href="{{ route('faq', app()->getLocale()) }}"><svg width="19" height="21"
                                             viewBox="0 0 19 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                             <g clip-path="url(#clip0_596_2167)">
                                                 <path
                                                     d="M8.70834 15.26H10.2917V13.6107H8.70834V15.26ZM9.50001 2.06592C5.13001 2.06592 1.58334 5.76026 1.58334 10.3122C1.58334 14.8642 5.13001 18.5585 9.50001 18.5585C13.87 18.5585 17.4167 14.8642 17.4167 10.3122C17.4167 5.76026 13.87 2.06592 9.50001 2.06592ZM9.50001 16.9093C6.00876 16.9093 3.16668 13.9488 3.16668 10.3122C3.16668 6.6756 6.00876 3.71518 9.50001 3.71518C12.9913 3.71518 15.8333 6.6756 15.8333 10.3122C15.8333 13.9488 12.9913 16.9093 9.50001 16.9093ZM9.50001 5.36444C7.75043 5.36444 6.33334 6.84053 6.33334 8.66296H7.91668C7.91668 7.75587 8.62918 7.0137 9.50001 7.0137C10.3708 7.0137 11.0833 7.75587 11.0833 8.66296C11.0833 10.3122 8.70834 10.1061 8.70834 12.7861H10.2917C10.2917 10.9307 12.6667 10.7245 12.6667 8.66296C12.6667 6.84053 11.2496 5.36444 9.50001 5.36444Z"
                                                     fill="currentColor" />
                                             </g>
                                             <defs>
                                                 <clipPath id="clip0_596_2167">
                                                     <rect width="19" height="19.7911" fill="white"
                                                         transform="translate(0 0.416504)" />
                                                 </clipPath>
                                             </defs>
                                         </svg>
                                         {{ __('FAQ') }}</a>
                                 </li>
                                 <li>
                                     <a href="{{ route('sectors', app()->getLocale()) }}"><svg width="19" height="21"
                                             viewBox="0 0 19 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                             <g clip-path="url(#clip0_596_2156)">
                                                 <path
                                                     d="M16.625 10.4937C16.625 5.9665 13.2525 2.89062 9.50001 2.89062C5.78709 2.89062 2.37501 5.90053 2.37501 10.5432C1.90001 10.8236 1.58334 11.3513 1.58334 11.9616V13.6108C1.58334 14.5179 2.29584 15.2601 3.16668 15.2601H3.95834V10.2298C3.95834 7.03852 6.43626 4.45742 9.50001 4.45742C12.5638 4.45742 15.0417 7.03852 15.0417 10.2298V16.0847H8.70834V17.734H15.0417C15.9125 17.734 16.625 16.9918 16.625 16.0847V15.0787C17.0921 14.823 17.4167 14.32 17.4167 13.7263V11.8296C17.4167 11.2524 17.0921 10.7494 16.625 10.4937Z"
                                                     fill="currentColor" />
                                                 <path
                                                     d="M7.12501 11.9618C7.56224 11.9618 7.91668 11.5926 7.91668 11.1371C7.91668 10.6817 7.56224 10.3125 7.12501 10.3125C6.68778 10.3125 6.33334 10.6817 6.33334 11.1371C6.33334 11.5926 6.68778 11.9618 7.12501 11.9618Z"
                                                     fill="currentColor" />
                                                 <path
                                                     d="M11.875 11.9618C12.3122 11.9618 12.6667 11.5926 12.6667 11.1371C12.6667 10.6817 12.3122 10.3125 11.875 10.3125C11.4378 10.3125 11.0833 10.6817 11.0833 11.1371C11.0833 11.5926 11.4378 11.9618 11.875 11.9618Z"
                                                     fill="currentColor" />
                                                 <path
                                                     d="M14.25 9.51264C13.87 7.16244 11.9067 5.36475 9.53958 5.36475C7.14083 5.36475 4.56 7.43457 4.76583 10.6836C6.72125 9.85073 8.19375 8.03655 8.61333 5.82654C9.65041 7.99532 11.78 9.4879 14.25 9.51264Z"
                                                     fill="currentColor" />
                                             </g>
                                             <defs>
                                                 <clipPath id="clip0_596_2156">
                                                     <rect width="19" height="19.7911" fill="white"
                                                         transform="translate(0 0.416504)" />
                                                 </clipPath>
                                             </defs>
                                         </svg>
                                         {{ __('Support') }}</a>
                                 </li>
                             </ul>
                         </div>
                         <div class="col">
                             <div class="social-icon">
                                 <ul>
                                     {{-- @if ($facebook_url != '') --}}
                                         <li>
                                             <a href="https://www.facebook.com/WOSULPOS/"><svg width="8" height="14"
                                                     viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                     <path
                                                         d="M6.9785 7.875L7.334 5.34133H5.11125V3.69715C5.11125 3.00398 5.42175 2.32832 6.41725 2.32832H7.42775V0.171172C7.42775 0.171172 6.51075 0 5.634 0C3.8035 0 2.607 1.21352 2.607 3.41031V5.34133H0.57225V7.875H2.607V14H5.11125V7.875H6.9785Z"
                                                         fill="currentColor" />
                                                 </svg>
                                             </a>
                                         </li>
                                     {{-- @endif --}}
                                     {{-- @if ($instagram_url != '') --}}
                                         <li>
                                             <a href="https://www.instagram.com/wosulpos/"><svg width="13" height="15"
                                                     viewBox="0 0 13 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                     <path
                                                         d="M6.50289 4.1311C4.65736 4.1311 3.16874 5.63403 3.16874 7.49731C3.16874 9.36059 4.65736 10.8635 6.50289 10.8635C8.34843 10.8635 9.83705 9.36059 9.83705 7.49731C9.83705 5.63403 8.34843 4.1311 6.50289 4.1311ZM6.50289 9.68579C5.31026 9.68579 4.33526 8.70434 4.33526 7.49731C4.33526 6.29028 5.30736 5.30884 6.50289 5.30884C7.69843 5.30884 8.67053 6.29028 8.67053 7.49731C8.67053 8.70434 7.69553 9.68579 6.50289 9.68579ZM10.7511 3.99341C10.7511 4.42993 10.4029 4.77856 9.97343 4.77856C9.54106 4.77856 9.19575 4.427 9.19575 3.99341C9.19575 3.55981 9.54397 3.20825 9.97343 3.20825C10.4029 3.20825 10.7511 3.55981 10.7511 3.99341ZM12.9594 4.79028C12.91 3.73853 12.6721 2.80688 11.9089 2.03931C11.1487 1.27173 10.2259 1.03149 9.18414 0.97876C8.11048 0.917236 4.8924 0.917236 3.81874 0.97876C2.7799 1.02856 1.85714 1.2688 1.09397 2.03638C0.330798 2.80395 0.0957532 3.7356 0.0435211 4.78735C-0.0174164 5.87134 -0.0174164 9.12036 0.0435211 10.2043C0.0928515 11.2561 0.330798 12.1877 1.09397 12.9553C1.85714 13.7229 2.777 13.9631 3.81874 14.0159C4.8924 14.0774 8.11048 14.0774 9.18414 14.0159C10.2259 13.9661 11.1487 13.7258 11.9089 12.9553C12.6692 12.1877 12.9071 11.2561 12.9594 10.2043C13.0203 9.12036 13.0203 5.87427 12.9594 4.79028ZM11.5723 11.3674C11.346 11.9416 10.9078 12.384 10.3362 12.6155C9.48013 12.9583 7.44888 12.8791 6.50289 12.8791C5.55691 12.8791 3.52276 12.9553 2.66964 12.6155C2.10089 12.387 1.66272 11.9446 1.43348 11.3674C1.09397 10.5032 1.17232 8.45239 1.17232 7.49731C1.17232 6.54224 1.09687 4.48852 1.43348 3.6272C1.65982 3.05298 2.09798 2.6106 2.66964 2.37915C3.52566 2.03638 5.55691 2.11548 6.50289 2.11548C7.44888 2.11548 9.48303 2.03931 10.3362 2.37915C10.9049 2.60767 11.3431 3.05005 11.5723 3.6272C11.9118 4.49145 11.8335 6.54224 11.8335 7.49731C11.8335 8.45239 11.9118 10.5061 11.5723 11.3674Z"
                                                         fill="currentColor" />
                                                 </svg>
                                             </a>
                                         </li>
                                     {{-- @endif --}}
                                     <li><a href="https://www.linkedin.com/company/wosul/"><svg width="17" height="13" viewBox="0 0 20 24"
                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M4.47679 21H0.330357V6.97971H4.47679V21ZM2.40134 5.06721C1.07545 5.06721 0 3.91408 0 2.52189C9.49017e-09 1.85318 0.252998 1.21185 0.703336 0.738991C1.15367 0.266136 1.76446 0.000488281 2.40134 0.000488281C3.03821 0.000488281 3.649 0.266136 4.09934 0.738991C4.54968 1.21185 4.80268 1.85318 4.80268 2.52189C4.80268 3.91408 3.72679 5.06721 2.40134 5.06721ZM19.9955 21H15.858V14.175C15.858 12.5485 15.8268 10.4625 13.7022 10.4625C11.5464 10.4625 11.2161 12.2297 11.2161 14.0578V21H7.07411V6.97971H11.0509V8.89221H11.1089C11.6625 7.79065 13.0147 6.62815 15.0321 6.62815C19.2286 6.62815 20 9.52971 20 13.2985V21H19.9955Z"
                                                fill="currentColor" />
                                        </svg>
                                    </a></li>
                                     @if ($youtube_url != '')
                                         <li>
                                             <a href="{{ $youtube_url }}"><svg width="17" height="13"
                                                     viewBox="0 0 17 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                     <path
                                                         d="M16.2225 2.63524C16.0371 1.94237 15.4909 1.39669 14.7974 1.2115C13.5404 0.875 8.50001 0.875 8.50001 0.875C8.50001 0.875 3.45962 0.875 2.2026 1.2115C1.50911 1.39672 0.962924 1.94237 0.777548 2.63524C0.440735 3.89111 0.440735 6.51137 0.440735 6.51137C0.440735 6.51137 0.440735 9.13162 0.777548 10.3875C0.962924 11.0804 1.50911 11.6033 2.2026 11.7885C3.45962 12.125 8.50001 12.125 8.50001 12.125C8.50001 12.125 13.5404 12.125 14.7974 11.7885C15.4909 11.6033 16.0371 11.0804 16.2225 10.3875C16.5593 9.13162 16.5593 6.51137 16.5593 6.51137C16.5593 6.51137 16.5593 3.89111 16.2225 2.63524ZM6.85151 8.89036V4.13237L11.0643 6.51143L6.85151 8.89036Z"
                                                         fill="currentColor" />
                                                 </svg>
                                             </a>
                                         </li>
                                    @endif
                                    {{-- @if ($twitter_url != '') --}}
                                         <li>
                                             <a href="https://twitter.com/wosulPOS"><svg width="14" height="12"
                                                     viewBox="0 0 14 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                     <path
                                                         d="M12.5609 3.14868C12.5698 3.27304 12.5698 3.39743 12.5698 3.52179C12.5698 7.31491 9.68275 11.6855 4.40609 11.6855C2.78045 11.6855 1.27031 11.2147 0 10.3974C0.230973 10.4241 0.453031 10.433 0.692891 10.433C2.03424 10.433 3.26903 9.97992 4.25507 9.20708C2.99365 9.18042 1.93654 8.35428 1.57232 7.21722C1.75 7.24385 1.92765 7.26162 2.11422 7.26162C2.37182 7.26162 2.62946 7.22607 2.86929 7.16392C1.55457 6.8974 0.568504 5.74259 0.568504 4.34793V4.31241C0.950469 4.52561 1.39467 4.65885 1.86545 4.6766C1.0926 4.16136 0.586277 3.28193 0.586277 2.287C0.586277 1.75402 0.728383 1.26544 0.977129 0.839039C2.38957 2.58015 4.51268 3.71719 6.89336 3.84157C6.84895 3.62838 6.82229 3.40632 6.82229 3.18423C6.82229 1.603 8.10149 0.314941 9.69158 0.314941C10.5177 0.314941 11.2639 0.661387 11.788 1.22103C12.4365 1.09667 13.0583 0.856813 13.6091 0.528141C13.3959 1.1944 12.9428 1.75404 12.3477 2.10935C12.9251 2.0472 13.4847 1.88726 13.9999 1.6652C13.6091 2.23371 13.1205 2.74003 12.5609 3.14868Z"
                                                         fill="currentColor" />
                                                 </svg>
                                             </a>
                                         </li>
                                    {{-- @endif --}}
                                 </ul>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
         <div class="navbar">
             <div data-aos="fade-down" data-aos-delay="200">
                 <a href="/{{ app()->getLocale() }}" class="logo">
                     <img loading="lazy"
                         src="{{ env('WEBSITE_MEDIA_URL') . '/storage/website_setting/' . $header_logo }}" />
                 </a>
             </div>
             <button class="toggle-menu" type="button">
                 <span></span>
                 <span></span>
                 <span></span>
             </button>
             <div class="header-right">
                 <nav class="navbar-menu">
                     <div class="navbar-inner">
                         <ul class="navbar-nav" id="menu">
                             <li @if (Route::current()->getName() == 'home') class="active" @endif><a
                                     href="{{ route('home', app()->getLocale()) }}">{{ __('Home') }}</a></li>
                             <li @if (Route::current()->getName() == 'about') class="active" @endif><a
                                     href="{{ route('sectors', app()->getLocale()) }}">{{ __('Sectors') }}</a></li>
                             <li @if (Route::current()->getName() == 'pricing') class="active" @endif><a
                                     href="{{ route('pricing', app()->getLocale()) }}">{{ __('Plans & Pricing') }}</a>
                             </li>
                             {{-- <li class="submenu-parent">
                                 <a href="#"></a>
                                 <ul>
                                     <li><a href="#">Plans 1</a></li>
                                     <li><a href="#">Plans 2</a></li>
                                     <li><a href="#">Plans 3</a></li>
                                     <li><a href="#">Plans 4</a></li>
                                     <li><a href="#">Plans 5</a></li>
                                     <li><a href="#">Plans 6</a></li>
                                 </ul>
                             </li> --}}
                             <li @if (Route::current()->getName() == 'marketplace') class="active" @endif><a
                                     href="{{ route('marketplace', app()->getLocale()) }}">{{ __('Marketplace') }}</a>
                             </li>
                             <li @if (Route::current()->getName() == 'contact') class="active" @endif><a
                                     href="{{ route('contact', app()->getLocale()) }}">{{ __('Contact Us') }}</a>
                             </li>
                         </ul>
                     </div>
                 </nav>
             </div>
             <div class="header-buttons">
                 <div class="mini-cart-wrap">
                     <button type="button" class="mini-cart-toggle"><svg width="20" height="20" viewBox="0 0 20 20"
                             fill="none" xmlns="http://www.w3.org/2000/svg">
                             <g clip-path="url(#clip0_770_503)">
                                 <path
                                     d="M7.49999 18.3332C7.96023 18.3332 8.33332 17.9601 8.33332 17.4998C8.33332 17.0396 7.96023 16.6665 7.49999 16.6665C7.03975 16.6665 6.66666 17.0396 6.66666 17.4998C6.66666 17.9601 7.03975 18.3332 7.49999 18.3332Z"
                                     stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" />
                                 <path
                                     d="M16.6667 18.3332C17.1269 18.3332 17.5 17.9601 17.5 17.4998C17.5 17.0396 17.1269 16.6665 16.6667 16.6665C16.2064 16.6665 15.8333 17.0396 15.8333 17.4998C15.8333 17.9601 16.2064 18.3332 16.6667 18.3332Z"
                                     stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" />
                                 <path
                                     d="M0.833344 0.833496H4.16668L6.40001 11.9918C6.47621 12.3755 6.68493 12.7201 6.98963 12.9654C7.29433 13.2107 7.67559 13.341 8.06668 13.3335H16.1667C16.5578 13.341 16.939 13.2107 17.2437 12.9654C17.5484 12.7201 17.7571 12.3755 17.8333 11.9918L19.1667 5.00016H5.00001"
                                     stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" />
                             </g>
                             <defs>
                                 <clipPath id="clip0_770_503">
                                     <rect width="20" height="20" fill="white" />
                                 </clipPath>
                             </defs>
                         </svg>
                         <span class="mini-cart-count">0</span>
                     </button>
                     <div class="mini-cart-box">
                         <div class="mini-cart-items">

                         </div>
                         <div class="mini-cart-total">
                             Total <b>0</b>
                         </div>
                         <a href="{{ route('checkout', app()->getLocale()) }}" id="checkout_btn"
                             class="btn btn-primary w-100">{{__('Checkout')}}</a>
                     </div>
                 </div>

                 <a href="{{ route('pricing', app()->getLocale()) }}"
                     class="btn btn-primary d-none d-md-inline-block">{{ __('Get Wosul') }}</a>
                 {{-- @if (session('logged_merchant_id') != null)
                     <a href="{{ route('login', app()->getLocale()) }}"
                         class="btn btn-outline-primary">{{ __('My Profile') }}</a>
                 @else
                 <a href="{{ route('login', app()->getLocale()) }}"
                     class="btn btn-outline-primary">{{ __('Sign In') }}</a>
                     @endif --}}
                 <!--session('logged_merchant_id')-->

                 @if (session('logged_merchant_id') != null)
                     <div class="mini-cart-wrap">
                         <a type="button" class="btn btn-outline-primary mini-cart-toggle">
                             {{ session('logged_merchant_name') }} </a>

                         <div class="mini-cart-box" style="min-width: 250px;">
                             <a href="{{ route('profile', app()->getLocale()) }}"
                                 class="btn btn-outline-primary w-100 btn-mini m-1">{{ __('My Profile') }}</a>
                             <a href="#"
                                 class="btn btn-outline-primary w-100 btn-mini m-1">{{ __('My Marketplaces') }}</a>
                             <a href="{{ route('my_orders', app()->getLocale()) }}" class="btn btn-outline-primary w-100 btn-mini m-1">{{ __('My Orders') }}</a>
                             <a href="{{ route('merchant_logout', app()->getLocale()) }}"
                                 class="btn btn-outline-danger w-100 btn-mini m-1 text-muted">{{ __('Logout') }}</a>
                         </div>

                     </div>
                 @else
                     <a href="{{ route('login', app()->getLocale()) }}"
                         class="btn btn-outline-primary">{{ __('Sign In') }}</a>
                 @endif
             </div>
         </div>
     </div>
 </header>

 <div class="responsive-menu">
     <button class="toggle-menu" type="button">
         <span></span>
         <span></span>
         <span></span>
     </button>
     <ul class="navbar-nav-responsive">
         <li class="active"><a href="{{ route('home', app()->getLocale()) }}">{{ __('Home') }}
             </a>
         </li>
         <li class=""><a href="{{ route('sectors', app()->getLocale()) }}">{{ __('Sectors') }}</a>
         </li>
         <li class=""><a
                 href="{{ route('pricing', app()->getLocale()) }}">{{ __('Plans & Pricing') }}</a></li>
         <li class=""><a
                 href="{{ route('marketplace', app()->getLocale()) }}">{{ __('Marketplace') }}</a>
         </li>
         <li class=""><a
                 href="{{ route('contact', app()->getLocale()) }}">{{ __('Contact Us') }}</a></li>
         {{-- <li class="submenu-parent">
             <a href="#">{{ __('Plans & Pricing') }}</a>
         <span class="submenu-toggle"></span>
         <ul>
             <li><a href="#">Plans 1</a></li>
             <li><a href="#">Plans 2</a></li>
             <li><a href="#">Plans 3</a></li>
             <li><a href="#">Plans 4</a></li>
             <li><a href="#">Plans 5</a></li>
             <li><a href="#">Plans 6</a></li>
         </ul>
         </li> --}}
     </ul>
 </div>
 <script>
     function set_cart_view() {
         let cart_items = document.querySelector(".mini-cart-items");
         let products = get_from_storage();
         document.querySelector("#checkout_btn").style.display = "block";
         if (products.length == 0) {
             document.querySelector("#checkout_btn").style.display = "none";
         }
         let cart_item = "",
             devicecount = 0,
             total = 0;
         for (let product in products) {
             cart_item += `<div class="mini-cart-item">
                                 <h4>${products[product].product_name}</h4>
                                 <h3>{{ __('SAR') }} ${products[product].total_amount}</h3>
                                 <button class="mini-cart-close" onclick="delete_from_cart(${products[product].product_id});">&times;</button>
                             </div>`;
             total += parseFloat(products[product].total_amount);
         }
         cart_items.innerHTML = cart_item;
         if(total<1){ localStorage.setItem("from_payment", 0); }
         document.querySelector(".mini-cart-total").innerHTML = `{{__('Total')}}  <b>{{__('SAR')}} ${total}</b>`;
         document.querySelector(".mini-cart-count").textContent = get_from_storage().length;
     }

     function get_from_storage() {
         let product_list = [];
         if (typeof(window.localStorage) !== "undefined") {
             if (localStorage.getItem("cart_items") != null) {
                 product_list = JSON.parse(localStorage.getItem("cart_items"));
                 return product_list;
             } else {
                 return [];
             }
         }
     }

     function delete_from_cart(product_id) {
         let products = get_from_storage();
         let product_list = [];
         for (let product in products) {
             if (parseInt(products[product].product_id) != parseInt(product_id)) {
                 product_list.push(products[product]);
             }
         }
         if (typeof(window.localStorage) !== "undefined") {
             localStorage.setItem("cart_items", JSON.stringify(product_list));
         }
         products = get_from_storage();
         if (products.length == 0) {
             products = get_from_buy_now();
         }
         if (products.length == 0) {
             if (window.location.href.includes("checkout")||window.location.href.includes("pricing")) {
                 window.location.reload();
             }
         }
         set_cart_view();
         if($('#enable_payment_gateway').val() == 1){
             let products = JSON.parse(localStorage.getItem("cart_items"));
             var subscription_id = 0;
             var in_cart = 0;
             for (let product in products) {
                 in_cart = 1;
                 if (products[product].product_type == "subscription") {
                     subscription_id = products[product].product_id
                     break;
                 }
             }
             $('#subscription_id').val(subscription_id);
             /*if(subscription_id<1 || subscription_id==''){
                 if(in_cart==1){
                     var url = "{{ route('login',app()->getLocale()) }}";
                 }else{
                     var url = "{{ route('pricing',app()->getLocale()) }}";
                 }
                 setTimeout(function() {window.location.href = url}, 300);
             }*/
         }
     }

     function get_from_buy_now() {
         let product_list = [];
         if (typeof(window.localStorage) !== "undefined") {
             if (localStorage.getItem("buy_now") != null) {
                 product_list.push(JSON.parse(localStorage.getItem("buy_now")));
                 return product_list;
             } else {
                 return [];
             }
         }
     }
     set_cart_view();
 </script>
