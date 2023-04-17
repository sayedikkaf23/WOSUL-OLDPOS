<!doctype html>
<html @if (App::getLocale() == 'ar') lang="ar" dir="rtl" @else lang="en" @endif>

<head>
    @include('includes/headerscript')
</head>

<body>

    <!-- Page Loader Start -->
    {{-- <div id="loader-wrapper">
        <div id="loader"><img loading="lazy" src="{{ env('WEBSITE_MEDIA_URL') }}/public.website/images/logo-light.png" alt=""
                width="" height=""></div>
        <div class="loader-section-wrap">
            <div class="loader-section"></div>
            <div class="loader-section"></div>
        </div>
    </div>
    <div class="header-menu-overlay"></div> --}}

    @include('includes/header')

    <div id="wrapper">
        <section class="bg-gradient inner-banner">
            <div class="inner-banner-logo-light">
                <img src="images/bg-logo-light.png" />
            </div>
            <div class="container">
                <div class="row justify-content-center align-items-center">
                    <div class="col-lg-7">
                        <div class="content">
                            <h2 data-aos="fade-up">{{ __('Terms & Conditions') }}</h2>
                            <ul class="breadcrumb-list" data-aos="fade-up">
                                <li>
                                    <a href="index.html">{{ __('Home') }}</a>
                                </li>
                                <li>{{ __('Terms & Conditions') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <section>
            <div class="container">

                @if (App::getLocale() == 'en')
                    <h4>OVERVIEW</h4>
                    <p>The following (Terms and Conditions) are a legal agreement between Wosul and you, as a current or
                        prospective customer of wosul for Information Technology Company,</p>
                    <p>Based on the invoice presented with a copy to you, the two parties agreed that the first would
                        provide
                        the point of sale service 'cashier' and fwtara and it was agreed on that. This agreement
                        replaces
                        any
                        previous agreements between the two parties, whether in writing or verbally. This agreement may
                        not
                        be modified without a written letter signed by the two parties.
                    </p>
                    <p>The aforementioned introduction is considered an integral part of this contract and an article of
                        its
                        clauses to be read and interpreted with For more, review the terms and conditions and terms of
                        service
                        below.</p>
                    <p>The aforementioned introduction is considered an integral part of this contract and an article of
                        its
                        clauses to be read and interpreted with For more, review the terms and conditions and terms of
                        service
                        below.</p>
                    <p>
                        Any new features or tools which are added to the current website shall also be subject to the
                        Terms
                        and
                        Conditions. You can review the most current version of the Terms and Conditions at any time on
                        this
                        page. We reserve the right to update, change or replace any part of these Terms and Conditions
                        by
                        posting updates and/or changes to our website. It is your responsibility to check this page
                        periodically for
                        changes. Your continued use of or access to the website following the posting of any changes
                        signifies
                        your understanding and acceptance of those changes.
                    </p>


                    <p>By continuing to use Wosul ’ Services, you agree to abide and be bound by those changes. If you
                        do
                        not
                        agree to any of the aforementioned changes, you must cease your use of the Services.
                    </p>

                    <h4>Account Registration
                    </h4>

                    <p>wosul offers a cloud-based management system to enable restaurants, coffee shops, food trucks,
                        retail
                        shops (Customers) to manage, assess, track, optimize, and scale their business operations and
                        meet
                        their
                        needs accordingly. You must open an account with us (a “wosul Account”) to use the Services.
                        During
                        registration, we will ask you for information, which may include but is not limited to, your
                        name
                        and other
                        personal information. You must provide accurate and complete information in response to our
                        questions,
                        and you must keep that information up-to-date. You are fully responsible for all activities
                        pertaining to your
                        wosul Account, including any actions taken by persons to whom you have granted access to said
                        wosul
                        Account. We reserve the right to change the account type, suspend or terminate the wosul Account
                        of
                        anyone who provides inaccurate, untrue, or incomplete information, or who fails to comply with
                        the
                        account
                        registration requirements, when it comes to both offline and online sales processes</p>

                    <h4>Account Cancellation & Data Protection
                    </h4>

                    <p>wosul reserves the right to cancel or terminate any wosul account in violation of the Terms and
                        Conditions
                        as well as Privacy Policies of this agreement; in which case wosul also reserves the right to
                        hold
                        on to any
                        personal information belonging to the customer in question in its database, for a period of one
                        month
                        starting from the cancellation date.</p>

                    <p>If, however, the customer chooses, for one reason or other, to cancel or terminate their wosul
                        account of
                        their own accord, wosul will immediately disregard and remove any personal information belonging
                        to
                        the
                        customer in question from its database.
                    </p>

                    <h4>Closing your account
                    </h4>
                    <p>You may close your wosul account at any time and without cost, but you will remain liable for any
                        outstanding Purchases as well as any fees or other charges incurred.
                    </p>
                    <p>In certain cases, we may not allow you to close your account, for the following reasons:
                    </p>
                    <p>In an attempt to evade an investigation;
                        In the presence of an open or pending Purchase(s) or Payment Transactions;
                        In the event that you owe money to wosul due to your use of the Services.</p>
                    <p>The privacy policies of WOSUL explain how we handle your personal data and how we protect your
                        privacy
                        when you use our services. By using our services, you agree that WOSUL can use this data in
                        accordance
                        with the privacy policies that we follow, and we also undertake to save the data in encrypted
                        methods and
                        secure servers with periodic backup copies, if you have an account on WOSUL we will display the
                        name
                        and
                        the logo of your company as one of our customers and mention this in the numbers of service
                        users.
                    </p>

                    <h4>Paying off:
                    </h4>
                    <p>In the event that the customer does not comply with the conditions and pay the dues, WOSUL has
                        the
                        right
                        to stop his services and WOSUL is not responsible for the loss of data after 14 days of stopping
                        the
                        payment, and it is also entitled to remove all his data if he remains inactive for 30
                        consecutive
                        days.
                    </p>
                    <h4>Devices</h4>
                    <p>Wosul provides the equipment and requirements according to the customer's need, but the
                        guarantee is on the agent in the region according to their terms.
                    </p>
                    <h6>Installation and Training:
                    </h6>
                    <p>- The second party must take into account that the first party will not start the installation or
                        training
                        until the services and facilities necessary to carry out these tasks in the region are provided,
                        such as
                        the Internet, electricity and anything else agreed upon.</p>
                    <p>- The second party must appoint one employee 'who is sufficiently familiar with the Information
                        Technology Department' responsible for coordinating all project phases and signing the delivery
                        and completion report for the first party. The completion document signed by the second party
                        will
                        be an official document with the first party and is restricted to it, and ensuring the equipment
                        of
                        wireless network and landlines will be finished after signing a “services job order” that the
                        printers
                        and screens are working well</p>

                    <h4>Requirements and Responsibilities:
                    </h4>
                    <p>- The first party is not responsible for the misuse of devices or the system or its use for other
                        than
                        the primary purpose of 'Wosul System'.
                    </p>
                    <p>- The second party provides all the electrical cables, networks and installations required before
                        the
                        implementation of the project, and it must be understood that it is not one of the tasks of the
                        first
                        party.If the service is presented by the first party, it will be as a paid service</p>
                    <p>- The first party is committed to providing technical support services and maintenance updates as
                        long as the second party continues to pay and uses the system.</p>
                    <p>- Our services offer some non-Wosul content. Responsibility for this content rests solely with
                        the
                        entity that made it.
                    </p>
                    <p>- With regard to your use of the services, we may send you service announcements, administrative
                        messages and other information.
                    </p>
                    <h4>Warranties and Disclaimers:
                    </h4>
                    <p>- We provide our services using a commercially reasonable level of skill and care and we hope you
                        enjoy using
                        them, but there are certain things that we do not promise you about our services, unless these
                        terms
                        or additional
                        conditions are explicitly specified behind it.
                    </p>
                    <p>Wosul or its suppliers or distributors do not make any special obligations about the services.
                        For
                        example: We do
                        not make any obligations regarding the content within the services, the specific functions of
                        the
                        services, their
                        availability, or their ability to meet your needs, we provide the services 'as is'.
                    </p>
                    <p>- We are committed to making our paid system the same as the one that you tried with the trial
                        version through
                        the previously shown, with the addition of some powers in it. Any future ideas or plans that are
                        not
                        applicable
                        unless signed.</p>
                    <p>Wosul is not responsible for any lawsuit, claim or penalty filed by the General Authority for
                        Zakat &
                        Tax or any
                        other government agency that imposes it.
                    </p>
                    <h6>Enterprise uses of our services:
                    </h6>
                    <p>If you use our services on behalf of an organization, that organization accepts these terms. This
                        organization will
                        maintain the foregoing and indemnify Wosul, its affiliates, officials, agents, and employees
                        against
                        any claim,
                        lawsuit, or legal action arising from it or related to the use of services or the violation of
                        these
                        terms, including any
                        liability or expenses arising from claims, losses, damages, lawsuits, judgments, and litigation
                        costs. And attorney
                        fees. Therefore, the party authorized to contact us is the party that initially signed the
                        contract,
                        and no one else
                        has the right to claim anything.</p>
                    <h4>About these Terms:
                    </h4>
                    <p>- We may amend these terms or any additional terms that apply to one of the services, for example
                        to
                        reflect
                        changes in the law or to our services. You should review these terms regularly. We will post a
                        notice of
                        modifications to these terms on our website and send it to your inbox. We will post notice of
                        additional modified
                        terms within the respective service. The changes will not be applied retroactively and will not
                        take
                        effect until
                        fourteen days after they are posted. However, changes related to new jobs for a service or
                        changes
                        that are made
                        for legal reasons will take effect immediately. If you do not agree to the amended terms for a
                        service, you must
                        stop using that service.
                    </p>
                    <p>- If there is a conflict between these terms and the additional terms, these additional terms
                        will
                        take effect with
                        respect to that conflict</p>
                    <p>- If you do not comply with these terms, and we do not take action immediately, this does not
                        mean
                        that we waive
                        any rights that we have (such as filing a lawsuit in the future).
                    </p>
                    <p>- If it turns out that a specific provision is not enforceable, this will not affect any other
                        provisions.
                    </p>
                    <p>- The laws of the Kingdom of Saudi Arabia shall apply to any disputes arising from or related to
                        these terms or
                        services. Litigations relating to all claims arising out of or related to these terms or
                        services
                        shall be exclusively filed
                        in the courts of the city of Riyadh.</p>
                @elseif(App::getLocale() == 'ar')
                    <h4>صفحة الأحكام والشروط
                    </h4>
                    <p>
                    تتضمن هذه الصفحة الأحكام والشروط الخاصة بنظام وصول، علمًا بأن أي تحديث وإضافة ميزات جديدة أخرى للنظام
                    تخضع لهذه الأحكام والشروط، ونحتفظ بالحق في تحديث الأحكام والشروط أو استبدالها عن طريق تغييرها أو تحديثها في موقعنا الرسمي
                    فيما يلي (الشروط والأحكام) هو اتفاق قانوني بين الشركة وبينك، بصفتك عميل محتمل أو حالي لشركة وصول الأولى لتقنية المعلومات
                       </p>
                       <p>تمهيد</p>
                    <p>
                    استنادا على الفاتورة المقدمة بنسخة لكم، اتفق الطرفان على أن يقوم الأول بتقديم خدمة نقاط البيع 'الكاشير " وتم الاتفاق على ذلك،
                    ويحل هذا الاتفاق محل أي اتفاقات سابقة بين الطرفين سواء كتابية أو شفهية ولا يجوز تعديل هذا الاتفاق بدون خطاب خطي موقع من الطرفين.
                    يعتبر التمهيد المذكور سابقا جزء لا يتجزأ من هذا العقد وبندًا من بنوده يقرأ ويفسر معه
                    وللمزيد راجع الشروط والأحكام وبنود الخدمة المذكورة أدناه 
                    </p>
                    <p>
                    من خلال إنشاء حساب وصول والاشتراك في إحدى منتجاتنا المقدمة أو زيارة موقعنا الإلكتروني الرسمي،
                    فإنك بذلك تقرّ وتوافق بالشروط والأحكام التالية بما في ذلك القواعد واللوائح والسياسات الإضافية المشار إليها هنا
                    تنطبق هذه الشروط والأحكام على جميع مستخدمي موقع وصول الرسمي، بما في ذك على سبيل المثال لا الحصر: 
                    المستخدمين من المتصفحات، والبائعين، والعمالة والتجار، أو المساهمين في المحتوى. 

                    </p>
                    <p>تخضع أيًضا أي ميزات أو أدوات جديدة تضاف إلى الموقع الحالي للشروط والأحكام. يمكنك مراجعة أحدث
                        إصدار من الشروط والأحكام في أي وقت
                        على هذه الصفحة. نحتفظ بالحق في تحديث أو تغيير أو استبدال أي جزء من هذه الشروط والاحكام عن طريق
                        نشر التحديثات أو التغييرات على
                        موقعنا. تقع على عاتقك مسؤولية مراجعة هذه الصفحة بشكل دوري لمعرفة التغييرات. إن استمرارك في
                        استخدام أو الوصول إلى موقع الويب بعد
                        نشر أي تغييرات يدل على فهمك وقبولك لهذه التغييرات</p>
                    <p>من خلال الإستمرار في استخدام خدمات وصول ، فإنك توافق على الإلتزام بهذه التغييرات والإلتزام بها.
                        إذا كنت لا توافق على أي من التغييرات
                        المذكورة أعلاه ، يجب عليك التوقف عن استخدام الخدمات.</p>
                    <h4>تسجيل الحساب
                    </h4>
                    <p> 
                    تقدم وصول نظام إدارة قائم على السحابة، لتمكين قطاع التجزئة والمطاعم من إدارة أنشطتهم التجارية والتتبع والتحسين وتوسيع نطاق عملياتهم التجارية وتلبية احتياجاتهم، ووفقًا لذلك يجب عليك الآتي:
                    فتح حساب معنا (حساب وصول) واستخدام المنتجات
                    أثناء التسجيل سنطلب منك معلومات محددة، على سبيل المثال لا الحصر: (المعلومات الشخصية)
                    يجب عليك تقديم المعلومات الصحيحة كاملة، مع تحديث مستمر لهذه المعلومات
                    يرجى الانتباه أنك المسؤول من الدرجة الأولى عن جميع الأنشطة المتعلقة بحساب وصول الخاص بك، وعن إتاحة الحساب لأشخاص آخرين تمنحهم حق الصلاحية في استخدامه
                    نحن نحتفظ بالحق في تغيير نوع الحساب أو تعليقه أو إنهاء حساب وصول إلى شخص يقدّم معلومات غير دقيقة وكاملة أو المعلومات التي تلزم بمتطلبات تسجيل الحساب أو المتعلقة بعمليات البيع عبر الانترنت وغير المتصلة بالإنترنت
                    </p>
                    <h4>إلغاء الحساب وحماية البيانات
                    </h4>
                    <p> 
                    تحتفظ وصول بالحق في إلغاء أو إنهاء أي حساب وصول في انتهاك للشروط والأحكام وكذلك سياسات الخصوصية لهذه الاتفاقية،في هذه الحالة تحتفظ وصول أيًضا بالحق بالاحتفاظ بأي معلومات شخصية تخص العميل المعني في قاعدة بياناتها، لمدة شهر واحد بدًءا من تاريخ الإلغاء
                    ومع ذلك، إذا اختار العميل، لسبب أو آخر، إلغاء أو إنهاء حساب وصول الخاص به من تلقاء نفسه، فإن وصول ستتجاهل على الفور وتزيل أي معلومات شخصية تخص العميل المعني من قاعدة بياناتها. 
                    توضح سياسات الخصوصية من وصول كيفية تعاملنا مع بياناتك الشخصية وكيفية حماية خصوصيتك عندما تستخدم خدماتنا. 
                    باستخدام خدماتنا، فإنك توافق على أن وصول يمكنها أن تستخدم هذه البيانات بما يتفق مع سياسات الخصوصية التي نتبعها، كما نتعهد بحفظ البيانات بطرق مشفرة وخوادم آمنه مع أخذ نسخ احتياطية دورية لها،
                    إذا كان لديك حساب على وصول، فقد نعرض اسم منشأتك، وصورة شعارها على أنك من عملائنا ونذكر ذلك في أعداد مستخدمي الخدمة
                    يمكنك إغلاق حساب وصول الخاص بك في أي وقت وبدون تكلفة، ولكنك ستظل مسؤول ًال عن أي مشتريات معلقة بالإضافة إلى أي رسوم أو مصاريف أخرى يتم تكبدها.
                    في بعض الحالات، قد لا نسمح لك بإغلاق حسابك للأسباب التالية: 
                    في محاولة للتهرب من التحقيق، في حالة وجود عملية (عمليات) شراء مفتوحة أو معلقة أو معاملات الدفع. 
                    </p>
                    <h4>الدفع</h4>
                    <p>


                    في حال عدم التزام العميل بالشروط ودفع المستحقات يحق لوصول إيقاف خدماته وتعتبر وصول غير مسؤولة عن فقدان 
                    البيانات بعد 14 يوم من توقفه عن السداد، كما يحق لها بإزالة جميع بياناته بحال بقائه غير نشط لمدة 30 يوم متتالية. في حال كنت مدينًا بمال لشركة وصول بسبب استخدامك للخدمات








                    </p>
                    <h4>الأجهزة
                    </h4>
                    <p> 

                    تقوم وصول بتوفير الأجهزة والمتطلبات حسب احتياج العميل غير أن الضمان على الوكيل الموجود في المنطقة وفق الأحكام الخاصة بهم. 


                    </p>
                    <h4>التركيب والتدريب:
                    </h4>
                    <p> 
                    يجب على الطرف الثاني أن يأخذ في الاعتبار أن الطرف الأول لن يبدأ في التركيب أو التدريب حتى يتم توفير الوسائل والمرافق اللازمة لتنفيذ هذه المهام في المنطقة مثل الإنترنت والكهرباء وأي شيء آخر يُتفق عليه. 

                    </p>
                    <p>
                    يجب على الطرف الثاني تعيين موظف واحد يكون على دراية كافية بقسم تقنية المعلومات مسؤول عن تنسيق جميع مراحل المشروع وتوقيع تقرير التسليم والإنجاز للطرف الأول،
                    وثيقة الإنجاز الموقعة من قبل الطرف الثاني ستكون وثيقة رسمية لدى الطرف الأول ومقيد بها، وضمان أجهزة الشبكة اللاسلكية والخطوط الأرضية سيكون منتهي بعد التوقيع على وثيقة أمر عمل للخدمات بأن الطابعات والشاشات تعمل بشكل جيد. 


                    </p>
                    <h4>المتطلبات والمسؤوليات
                    </h4>
                    <p>
                    لطرف الأول غير مسؤول عن سوء استخدام الأجهزة أو النظام أو استخدامها في غير الغاية الأساسية وهي نظام وصول. 
                    </p>
                    <p>االطرف الثاني يوفر جميع أعمال الكابلات الكهربائية والشبكات والتركيبات المطلوبة قبل تنفيذ المشروع ويجب أن يفهم أنها ليست من مهام الطرف الأول، وفي حال تقديمها تكون كخدمة مدفوعة</p>
                    <p>الطرف الأول ملتزم بتقديم خدمات الصيانة طالما أن الطرف الثاني مستمر بالدفع ويقوم باستخدام النظام. 
                    </p>
                    <p>
                    تعرض خدماتنا بعض المحتويات التي تتبع وصول. وتقع مسؤولية هذه المحتويات على عاتق الكيان الذي أتاحها فقط. 
                    </p>
                    <p>
                    فيما يتعلق باستخدامك للخدمات، يجوز أن نرسل إليك إعلانات الخدمات ورسائل إدارية ومعلومات أخرى عبر قنوات تواصل أخرى غير البريد الإلكتروني. 
                    </p>
                    <h4>
                    الضمانات وإخلاء المسؤولية:
                    </h4>
                    <p>
                    إننا نقدم خدماتنا باستخدام مستوى معقول تجاريًا من المهارة والعناية ونأمل أن تستمتع باستخدامها، ولكن هناك أشياء معينة لا نعدك بها بشأن خدماتنا، مالم تحدد صراحة هذه الشروط أو الشروط الإضافية خلاف ذلك،
                     لا تتعهد وصول أو مورودها أو موزعوها بأي التزامات خاصة حول الخدمات على سبيل المثال: لا نقدم أي التزامات بخصوص المحتوى ضمن الخدمات، أو الوظائف المحددة للخدمات،أو توافرها، أو قدرتها على تلبية احتياجاتك، إننا نقدم الخدمات” كما هي'
                    </p>
                    <p>إننا نلتزم أن يكون نظامنا المدفوع هو نفس النظام الذي قمت بتجربته بالنسخة المجانية من خلال المعروض
                        سابقا مع إضافة
                        لبعض الصلاحيات فيه. أي أفكار او خطط مستقبلية غير واجبة التنفيذ مالم يتم التوقيع عليها </p> 
                    <p>شركة وصول ليست مسؤولة عن أي دعوى أو مطالبة أو عقوبة مرفوعة من الهيئة العامة للزكاة والدخل أو أي
                        جهة حكومية أخرى
                        تفرضها.</p>
                    <h4>استخدامات المؤسسات لخدماتنا:
                    </h4>
                    <p>
                    إذا كنت تستخدم خدماتنا بالنيابة عن مؤسسة، فتلك المؤسسة تقبل هذه البنود. ستحافظ هذه المؤسسة على ما ذكر وتعوض وصول وشركاتها التابعة لها ومسؤوليها ووكلاءها والموظفين ضد أية مطالبة أو دعوي قضائية أو إجراء قانوني ينشأ 
                    عنها أو يرتبط باستخدام الخدمات أو بانتهاك هذه البنود بما في ذلك أي مسؤولية أو نفقات تنشأ عن المطالبات والخسائر والأضرار والدعاوي القضائية والأحكام وتكاليف التقاضي وأتعاب المحاماة لذلك فإن الجهة المخولة للاتصال معنا هي الجهة التي قامت بتوقيع العقد، ولا يحق لغيرها المطالبة بأي شيء. 
                    </p>
                    <h4>حول هذه البنود:
                    </h4>
                    <p>
                    يجوز أن نعدل هذه البنود أو أي بنود إضافية تسري على إحدى الخدمات، على سبيل المثال لتعكس تغييرات طرأت على القانون أو على خدماتنا. يجب عليك مراجعة هذه البنود بانتظام. سننشر إشعارا بالتعديلات التي تتم لهذه البنود في موقعنا
                    ونرسل على بريدك الخاص، وسننشر إشعارا بالبنود الإضافية المعدلة ضمن الخدمة المعنية. لن يتم تطبيق التغييرات بأثر رجعي ولن يسري مفعولها إلا بعد أربعة عشر يوما من نشرها. ومع ذلك ستصبح التغييرات التي تتعلق بوظائف جديدة
                    إحدى الخدمات أو التغييرات التي يتم إجراؤها ألسباب قانونية سارية على الفور. وإذا لم توافق على البنود المعدلة إحدى الخدمات، فيجب عليك التوقف عن استخدام تلك الخدمة. 
                    
                    </p>
                    <p>
                    إذا كان هناك تعارض بين هذه البنود والبنود الإضافية، فسيسري مفعول هذه البنود الإضافية فيما يتعلق بذلك التعارض.         
                </p> 

                    <p>
                    إذا لم تلتزم بهذه البنود، ولم نتخذ إجراء على الفور، فإن هذا لا يعني أننا نتنازل عن أي حقوق نمتلكها (مثل رفع دعوى قضائية في المستقبل)
                    </p>
                    <p>
                    إذا تبين أن بندًا معينا غير قابل للتنفيذ، فإن ذلك لن يؤثر في أي بنود أخرى. 
                    </p>
                    <p>
                    تسري قوانين المملكة العربية السعودية، على أية نزاعات ناجمة عن أو متصلة بهذه البنود أو الخدمات، تقام الدعاوى المتعلقة بجميع المطالبات التي تنشأ عن أو تتعلق بهذه البنود أو الخدمات حصريا في محاكم مدينة الرياض. 
                    
                    </p>
                @endif

            </div>
        </section>



    </div>

    @include('includes/footer')


    <a href="javascript:void(0)" class="scrollToTop"><svg xmlns="http://www.w3.org/2000/svg" width="49.198"
            height="78.727" viewBox="0 0 49.198 78.727">
            <path id="arrow-up-long-solid"
                d="M47.761,28.055a4.918,4.918,0,0,1-6.958,0L29.526,16.784v57a4.92,4.92,0,0,1-9.84,0v-57L8.4,28.055A4.92,4.92,0,1,1,1.446,21.1L21.127,1.416a4.918,4.918,0,0,1,6.958,0L47.766,21.1A4.923,4.923,0,0,1,47.761,28.055Z"
                transform="translate(-0.005 0.025)" fill="currentColor" />
        </svg>
    </a>

    @include('includes/footerscript')


</body>

</html>
