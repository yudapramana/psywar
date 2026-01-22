@extends('layouts.app')

@section('title', 'About SYMCARD 2026')

@section('content')

    {{-- ================= PAGE TITLE ================= --}}
    <div class="page-title dark-background" style="background-image: url('{{ asset('projects/assets/img/events/showcase-9.webp') }}');">
        <div class="container position-relative">
            <h1>About SYMCARD 2026</h1>
            <p>
                11<sup>th</sup> Padang Symposium on Cardiovascular Disease<br>
                Cardiology 360°: Integrating Knowledge, Technology, and Practice
            </p>

            <nav class="breadcrumbs">
                <ol>
                    <li><a href="{{ route('landing') }}">Home</a></li>
                    <li class="current">About</li>
                </ol>
            </nav>
        </div>
    </div>
    {{-- /PAGE TITLE --}}

    <section class="section py-5">
        <div class="container">
            <div class="row">

                {{-- SIDEBAR --}}
                <div class="col-lg-3 mb-4">
                    @include('pages.about.partials.sidebar')
                </div>

                {{-- CONTENT --}}
                <div class="col-lg-9">

                    <div class="content">
                        <h3>Advancing Cardiovascular Care Through Science</h3>

                        <p>Cardiovascular disease remains the leading cause of mortality worldwide and continues to pose a major public health challenge, particularly in developing countries including Indonesia. The burden of cardiovascular disease is steadily increasing as a result of population aging, lifestyle changes, and the growing prevalence of cardiovascular risk factors. These conditions contribute to rising morbidity, mortality, and healthcare demands related to cardiovascular disorders.</p>
                        <p>Alongside this growing burden, rapid advances in cardiovascular science and medical technology have significantly transformed diagnostic and therapeutic strategies. Innovations in non-invasive and invasive imaging, evidence-based pharmacological therapies, and interventional cardiology procedures require healthcare professionals to continuously update their knowledge and clinical skills. Adherence to national and international clinical guidelines is essential to ensure optimal patient outcomes in modern cardiovascular care.</p>
                        <p>The Padang Symposium on Cardiovascular Disease (SYMCARD) is organized as a scientific forum dedicated to knowledge exchange, professional development, and academic discussion for general practitioners, residents, and specialists involved in cardiovascular care. The symposium emphasizes evidence-based practice, practical clinical application, and discussion of real-world cardiovascular cases, with the aim of improving the quality of cardiovascular services across diverse healthcare settings.</p>
                        <p>SYMCARD is a regular scientific meeting organized by the Department of Cardiology and Vascular Medicine, Faculty of Medicine, Universitas Andalas, in collaboration with RSUP Dr. M. Djamil Padang and the Indonesian Society of Cardiology (PERKI), West Sumatra Branch. In 2026, SYMCARD marks its 11th annual meeting under the theme “Cardiology 360°: Integrating Knowledge, Technology, and Practice”, highlighting the importance of a comprehensive and integrated approach to contemporary cardiovascular medicine.</p>
                        <p>SYMCARD 2026 will be held as an onsite event from June 5–7, 2026, at The ZHM Premiere Hotel, Padang. The scientific program will include plenary sessions, scientific symposia, hands-on workshops, moderated poster sessions, clinical case presentations, pharmaceutical exhibitions, and interactive educational activities such as Cardiology in Jeopardy. Through these activities, SYMCARD 2026 aims to strengthen professional networking, enhance clinical competence, and promote the implementation of safe, effective, and patient-centered cardiovascular care.</p>
                    </div>




                </div>
            </div>
        </div>
    </section>

    {{-- ================= ABOUT SECTION ================= --}}
    <section id="about" class="about section mt-0 pt-0">
        <div class="container">


            {{-- ================= WHO SHOULD ATTEND ================= --}} <div class="row mt-5">
                <div class="col-12">
                    <div class="audience-section">
                        <h3>Who Should Attend?</h3>
                        <p class="text-center"> SYMCARD 2026 is designed for healthcare professionals involved in cardiovascular care and education. </p>
                        <div class="row gy-4 mt-4">
                            <div class="col-lg-2 col-md-4 col-6">
                                <div class="audience-item">
                                    <div class="audience-icon"> <i class="bi bi-heart-pulse"></i> </div>
                                    <h5>Cardiologists</h5>
                                    <p>Specialists in cardiovascular medicine</p>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-4 col-6">
                                <div class="audience-item">
                                    <div class="audience-icon"> <i class="bi bi-person-badge"></i> </div>
                                    <h5>Residents</h5>
                                    <p>Cardiology and internal medicine trainees</p>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-4 col-6">
                                <div class="audience-item">
                                    <div class="audience-icon"> <i class="bi bi-hospital"></i> </div>
                                    <h5>General Practitioners</h5>
                                    <p>Frontline cardiovascular care providers</p>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-4 col-6">
                                <div class="audience-item">
                                    <div class="audience-icon"> <i class="bi bi-mortarboard"></i> </div>
                                    <h5>Medical Students</h5>
                                    <p>Future healthcare professionals</p>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-4 col-6">
                                <div class="audience-item">
                                    <div class="audience-icon"> <i class="bi bi-clipboard-pulse"></i> </div>
                                    <h5>Allied Health</h5>
                                    <p>Nurses & allied cardiovascular staff</p>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-4 col-6">
                                <div class="audience-item">
                                    <div class="audience-icon"> <i class="bi bi-lightbulb"></i> </div>
                                    <h5>Researchers</h5>
                                    <p>Clinical & academic researchers</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> {{-- /WHO SHOULD ATTEND --}} </div>
    </section> {{-- /ABOUT SECTION --}}

@endsection
