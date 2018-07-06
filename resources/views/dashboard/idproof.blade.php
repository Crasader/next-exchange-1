@extends('_layouts.main')
@section('content')

    @include('_layouts.topnav')


    <section id="profile" class="ptb40 bg--secondary">
        <div class="container idProof__container">
            <div class="row">
                <div class="col-lg-12">
                    @if (Session::has('success_msg'))
                        <div class="alert alert-success alert-message idProof__alert">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">
                                <span aria-hidden="true">&times;</span>
                            </a>
                            <i class="fa fa-check" aria-hidden="true"></i>
                            {{ Session::get('success_msg')}}
                        </div>
                    @endif
                    @php
                        $idProofTab = "active";
                        $idProofAria = "true";
                        $adressProofAria = "false";
                         $addressProofTab = "";
                    @endphp
                    @if(empty($errors->first('id_proof')) && !empty($errors->first('address_proof')))
                        @php
                            $idProofTab = "";
                            $idProofAria = "false";
                            $adressProofAria = "true";
                            $addressProofTab = "active show";

                        @endphp
                    @endif
                    {!! Form::open(['route' => 'profile_files','method' => 'POST', 'id'=>'profile_files', 'class' => 'form-horizontal', 'files'=> true ]) !!}
                    <section class="c-graph-card ptb40 idProof__card">
                        <div class="upload-section-header">
                            <div class="text-center icon idProof__iconContainer"><img
                                        src="{{ asset('img/address-icon.svg') }}"></div>
                            <h3 class="text-center">Verify your Identity</h3>
                            <p class="text-center">Upload a picture or scan of 2 separate identity documents below:</p>
                        </div>
                        <div class="row justify-content-md-center justify-content-sm-center">
                            <div class="col-md-8 upload-tab-block">
                                <ul class="nav nav-tabs nav-justified">
                                    <li class="nav-item">
                                        <a class="nav-link idProof__navLink {{ $idProofTab }}"
                                           aria-expanded="{{ $idProofAria }}"
                                           data-toggle="tab" href="#panel1" role="tab">ID Proof</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link idProof__navLink {{ $addressProofTab }}"
                                           aria-expanded="{{ $adressProofAria }}" data-toggle="tab" href="#panel2"
                                           role="tab">Address Proof</a>
                                    </li>
                                </ul>
                                <div class="tab-content card idProof__innerCard">
                                    <div class="tab-pane upload-tab fade in show {{ $idProofTab }}" id="panel1"
                                         role="tabpanel" aria-expanded="true">
                                        <div class="upload-main-box row">
                                            @if(empty($user->id_proof))
                                                <div class="row idProof__row">
                                                    <div class="col idProof__col">
                                                        <div class="upload-content-block">
                                                            <h4>Your Document must be:</h4>
                                                            <ul>
                                                                <li>
                                                                    A Government issued
                                                                    <ul class="sub-list">
                                                                        <li>Passport</li>
                                                                        <li>Driver License</li>
                                                                        <li>National ID card</li>
                                                                    </ul>
                                                                </li>
                                                                <li>
                                                                    Within expiry date
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>

                                                    <div class="col idProof__col">

                                                        <div class="drage-box @if($errors->first('id_proof')) file-upload-error @endif">

                                                            <div class="file-upload">
                                                                <div class="error-block">
                                                                    @if ($errors->has('id_proof'))
                                                                        <div>
                                                                            <strong>{{ $errors->first('id_proof') }}</strong>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <input class="file-upload-input2" id="id_proof"
                                                                       name="id_proof" type='file'
                                                                       onchange="idProof(this);"/>
                                                                <div class="drag-text">
                                                                    <img src="{{ asset('img/uploadfile.svg') }}"
                                                                         width="150"
                                                                         alt=""
                                                                         class="drag-img-icon justify-content-center"
                                                                         id="id_proof">
                                                                    <h3>Drag file here to upload or <br>select a file
                                                                        from
                                                                        your device</h3>
                                                                </div>

                                                                <div class="file-upload-content2">
                                                                    <div class="image-title-wrap">
                                                                        <button type="button" onclick="removeIdProof()"
                                                                                class="remove-image">Remove <span
                                                                                    class="image-title2">Uploaded Image</span>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>


                                                </div>
                                                <div class="btn-section">
                                                    <input type="submit" class="c-btn idProof__btn" value="Submit"
                                                           id="id_proof_button"
                                                           disabled="true"/>
                                                </div>

                                            @else
                                                <div class="warning-block">
                                                    <span><img src="{{ asset('img/caution-icon-grey.svg') }}"></span>
                                                    <span>Waiting For Approval</span>
                                                </div>

                                            @endif


                                        </div>
                                    </div>


                                    <div class="tab-pane upload-tab fade {{ $addressProofTab }}" id="panel2"
                                         role="tabpanel">
                                        <div class="upload-main-box row">
                                            @if(empty($user->address_proof))
                                                <div class="row idProof__row">
                                                    <div class="col idProof__col">
                                                        <div class="upload-content-block">
                                                            <h4>Your Document must be:</h4>
                                                            <ul>
                                                                <li>
                                                                    A recent, less than 1 month old:
                                                                    <ul class="sub-list">
                                                                        <li>Electricity Bill</li>
                                                                        <li>Water Bill</li>
                                                                        <li>Or another official document with your
                                                                            address
                                                                        </li>
                                                                    </ul>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="col idProof__col">

                                                        <div class="drage-box @if($errors->first('address_proof')) file-upload-error @endif">

                                                            <div class="file-upload">
                                                                <div class="error-block">
                                                                    @if ($errors->has('address_proof'))
                                                                        <div>
                                                                            <strong>{{ $errors->first('address_proof') }}</strong>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <input class="file-upload-input" id="address_proof"
                                                                       name="address_proof" type='file'
                                                                       onchange="addressProof(this);"/>
                                                                <div class="drag-text">
                                                                    <img src="{{ asset('img/uploadfile.svg') }}"
                                                                         width="150"
                                                                         alt=""
                                                                         class="drag-img-icon justify-content-center">
                                                                    <h3>Drag file here to upload or <br>select a file
                                                                        from
                                                                        your device</h3>
                                                                </div>
                                                                <div class="file-upload-content">
                                                                    <div class="image-title-wrap">
                                                                        <button type="button"
                                                                                onclick="removeAddressProof()"
                                                                                class="remove-image">Remove <span
                                                                                    class="image-title">Uploaded Image</span>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>


                                                </div>
                                                <div class="btn-section">
                                                    <input type="submit" class="c-btn idProof__btn" value="Submit"
                                                           id="address_button"
                                                           disabled="true"/>
                                                </div>


                                            @else
                                                <div class="warning-block">
                                                    <span><img src="{{ asset('img/caution-icon-grey.svg') }}"></span>
                                                    <span>Waiting For Approval</span>
                                                </div>

                                            @endif


                                        </div>

                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <div class="center">
                            <small>All uploaded documents are stored securely with a 256-bit encryption method.</small>
                        </div>
                    </section>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
        <script type="text/javascript">

            $(function () {
                $('[data-toggle="tooltip"]').tooltip()
            });

            /* Upload js end */
            function addressProof(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $("#address_button").prop("disabled", false);
                        $('.image-upload-wrap').hide();

                        $('.file-upload-image').attr('src', e.target.result);
                        $('.file-upload-content').show();

                        $('.image-title').text(input.files[0].name);
                    };
                    reader.readAsDataURL(input.files[0]);
                } else {
                    removeAddressProof();
                }
            }

            function idProof(input) {
                if (input.files && input.files[0]) {

                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $("#id_proof_button").prop("disabled", false);
                        $('.image-upload-wrap2').hide();

                        $('.file-upload-image2').attr('src', e.target.result);
                        $('.file-upload-content2').show();

                        $('.image-title2').text(input.files[0].name);
                    };
                    reader.readAsDataURL(input.files[0]);
                } else {
                    removeIdProof();
                }
            }

            function removeAddressProof() {
                $('#address_proof').val(null);
                $("#address_button").prop("disabled", true);
                $('.file-upload-input').replaceWith($('.file-upload-input').clone());
                $('.file-upload-content').hide();
                $('.image-upload-wrap').show();
            }

            function removeIdProof() {
                $('#id_proof').val(null);
                $("#id_proof_button").prop("disabled", true);
                $('.file-upload-input2').replaceWith($('.file-upload-input2').clone());
                $('.file-upload-content2').hide();
                $('.image-upload-wrap2').show();
            }


            $('.image-upload-wrap').bind('dragover', function () {
                $('.image-upload-wrap').addClass('image-dropping');
            });
            $('.image-upload-wrap').bind('dragleave', function () {
                $('.image-upload-wrap').removeClass('image-dropping');
            });

            $('.image-upload-wrap2').bind('dragover', function () {
                $('.image-upload-wrap2').addClass('image-dropping2');
            });
            $('.image-upload-wrap2').bind('dragleave', function () {
                $('.image-upload-wrap2').removeClass('image-dropping2');
            });
        </script>

@endsection