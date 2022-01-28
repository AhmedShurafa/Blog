@extends('layouts.app')
@section('content')
    <!-- Start Contact Area -->
    <section class="wn_contact_area bg--white pt--80 pb--80">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-12">
                    <div class="contact-form-wrap">
                        <h2 class="contact__title">Get in touch</h2>
                        <p>Nam liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer possim assum. </p>


                        {!! Form::open(['route'=>'addContact','method'=>'post','id'=>'contact-form']) !!}
                            <div class="single-contact-form">
                                {!! Form::text('name', old('name'), ['placeholder'=>'Full Name*']) !!}
                            </div>

                            <div class="single-contact-form space-between">
                                {!! Form::text('email', old('email'), ['placeholder'=>'Email*']) !!}

                                {!! Form::text('moblie', old('moblie'), ['placeholder'=>'Moblie*']) !!}
                            </div>
                            <div class="single-contact-form">
                                {!! Form::text('title', old('title'), ['placeholder'=>'Subject*']) !!}
                            </div>
                            <div class="single-contact-form message">
                                {!! Form::textarea('message', old('message'), ['placeholder'=>'Type your message here..']) !!}
                            </div>
                            <div class="contact-btn">
                                {!! Form::button('Send Email', ['type'=>'submit']) !!}
                            </div>
                            {!! Form::close() !!}
                        </div>
                    <div class="form-output">
                        <p class="form-messege">
                    </div>
                </div>
                <div class="col-lg-4 col-12 md-mt-40 sm-mt-40">
                    <div class="wn__address">
                        <h2 class="contact__title">Get office info.</h2>
                        <p>Claritas est etiam processus dynamicus, qui sequitur mutationem consuetudium lectorum. Mirum est notare quam littera gothica, quam nunc putamus parum claram, anteposuerit litterarum formas humanitatis per seacula quarta decima et quinta decima. </p>
                        <div class="wn__addres__wreapper">

                            <div class="single__address">
                                <i class="icon-location-pin icons"></i>
                                <div class="content">
                                    <span>address:</span>
                                    <p>666 5th Ave New York, NY, United</p>
                                </div>
                            </div>

                            <div class="single__address">
                                <i class="icon-phone icons"></i>
                                <div class="content">
                                    <span>Phone Number:</span>
                                    <p>716-298-1822</p>
                                </div>
                            </div>

                            <div class="single__address">
                                <i class="icon-envelope icons"></i>
                                <div class="content">
                                    <span>Email address:</span>
                                    <p>716-298-1822</p>
                                </div>
                            </div>

                            <div class="single__address">
                                <i class="icon-globe icons"></i>
                                <div class="content">
                                    <span>website address:</span>
                                    <p>716-298-1822</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Contact Area -->

@endsection
