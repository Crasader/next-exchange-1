@extends('_layouts.main')

@section('content')
    @include('_layouts.topnav')
    <section class="bg--secondary space--sm">

        <style>
            .pro-header {
                background-color: white;
                padding-top: 50px;
            }

            .pro-coverimage {
                background-position: 50%;
                background-repeat: no-repeat;
                background-size: cover;
                margin: 0 auto;
                min-height: 300px;
                max-height: 300px;

            }

            .survey-progress-label {
                vertical-align: middle;
                margin: 0 10px;
                color: #8DC63F;
            }

            .survey-progress-bg {
                display: inline-block;
                vertical-align: middle;
                position: relative;
                width: 100%;
                height: 4px;
                border-radius: 2px;
                overflow: hidden;
                background: #eee;
            }

            .survey-progress-fg {
                position: absolute;
                top: 0;
                bottom: 0;
                height: 100%;
                left: 0;
                margin: 0;
                background: #8DC63F;
            }

            /* User-generated content */
            .ugc ul {
                list-style: none;
            }

            .ugc li {
                position: relative;
                padding-left: 15px;
            }

            .ugc li:before {
                position: absolute;
                top: 10px;
                margin: 0 0 0 -12px;
                width: 5px;
                height: 1px;
                background: #000;
                content: "";
                vertical-align: middle;
                display: inline-block;
            }

            #ico .btn {
                border-radius: 0px !important;
                color: #0a0a0a;
                border-color: #0a0a0a;
            }

            #ico .btn-outline {
                background-color: transparent;
                border: 1px solid;
            }

            #ico .btn-outline :hover {
                color: #fff;
            }

            #ico .btn-more-sm {
                font-size: 15px;
                font-weight: 500;
                -moz-border-radius: 3px;
                -webkit-border-radius: 3px;
                border-radius: 3px;
                padding: 6px 20px;
                line-height: 1.7em;
                background: transparent;
                border: 2px solid;
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;
                -moz-transition: all 0.2s;
                -webkit-transition: all 0.2s;
                transition: all 0.2s;
                position: relative;
            }

            #ico .btn-more-sm:hover {
                background: rgba(255, 255, 255, 0.2);
                border: 2px solid transparent;
                padding: 6px 34px 6px 14px !important;
            }

            #ico .btn-more-sm:after {
                font-size: 24px;
                line-height: 1em;
                content: "\35";
                opacity: 0;
                position: absolute;
                margin-left: -20px;
                -moz-transition: all 0.2s;
                -webkit-transition: all 0.2s;
                transition: all 0.2s;
                top: 7px;
                right: 5px;
                font-family: 'ETmodules';
                speak: none;
                font-style: normal;
                font-weight: normal;
                font-variant: normal;
                text-transform: none;
                line-height: 1;
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;
            }

            #ico .btn-more-sm:hover:after {
                opacity: 1;
                margin-left: 0;
            }

            .f300 {
                font-weight: 700 !important;
            }

        </style>


        <div class="pro-header" id='ico'>


            <div class='container'>

                <div class='col-sm-12'>

                    <div class="row">
                        <div class='col-sm-2'>

                            <img src="/img/noimage.png" class="img-fluid"/>


                        </div>

                        <div class="col-sm-7">
                            <h1>MINE</h1><h4>Invest in mining facilities in the Netherlands <img
                                        src="/img/industries/industry.png" alt="industry" width="20" height=""></h4>
                            <p class='black1'>


                                <i class="icon icon--map-pin black" style='font-size: 16px'></i> Eindhoven - The
                                Netherlands</p>

                        </div>
                        <div class="col-sm-3">
                            <div class='pull-right'>
                                <br><br>

                                <a class='btn btn-outline' href='/projects/list'>See all projects</a>
                                <div class='btn-group'>

                                </div>
                            </div>
                        </div>


                        <br>

                    </div>
                </div>
            </div>


            <!-- Tab panes -->
            <div class="tab-content">

                <!-- OVERVIEW TAB -->
                <div role="tabpanel" class="tab-pane active" id="sectionA">


                    <style>
                        .pro-coverimage {
                            position: relative;
                            min-height: 100px;
                        }

                        .pro-coverimage .dropzone {
                            position: absolute;
                            min-height: inherit;
                            padding: 0;
                            opacity: 0.5;
                            right: 10px;
                            top: 10px;
                            left: 10px;
                        }

                        .white-panel-widget {
                            background: #fff url('/img/icons/back-shadow-style.png') repeat-x left bottom;
                            border-radius: 2px;
                            margin: 0 0 0 0;
                            padding: 20px 30px 30px 30px;
                            color: #333;
                        }

                        .white-panel-widget h3 {
                            color: #333;
                            padding-bottom: 20px;
                        }

                    </style>

                    <div class='pro-coverimage about-header-previews' style="background-image:url('')">
                    </div>

                    <div class='bg-grey' style='margin-top: -50px'>

                        <div class='container ptb20'>
                            <div class='row'>
                                <div class='col-md-8'>

                                    <div class='white-panel-widget'>
                                        <h3>Invest in mining facilities in the Netherlands</h3>
                                        <div class='pro-images'>


                                            <a class="about-full-preview" href="javascript:void(0);">
                                                <img src="/data/project/1797/images/about-01.jpg" alt="" width="350"
                                                     height=""/>
                                            </a>


                                            <ul class="about-gallery-previews">
                                                <li>
                                                    <a href="javascript:void(0);">
                                                        <img src="/data/project/1797/images/about-01.jpg" alt=""
                                                             width="75" height="45"/>
                                                    </a>
                                                    <button class="delete-about"
                                                            data-imgurl="/data/project/1797/images/about-01.jpg"
                                                            title="Delete"><span class="glyphicon glyphicon-remove"
                                                                                 aria-hidden="true"></span></button>

                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);">
                                                        <img src="/data/project/1797/images/about-02.jpg" alt=""
                                                             width="75" height="45"/>
                                                    </a>
                                                    <button class="delete-about"
                                                            data-imgurl="/data/project/1797/images/about-02.jpg"
                                                            title="Delete"><span class="glyphicon glyphicon-remove"
                                                                                 aria-hidden="true"></span></button>

                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);">
                                                        <img src="/data/project/1797/images/about-03.jpg" alt=""
                                                             width="75" height="45"/>
                                                    </a>
                                                    <button class="delete-about"
                                                            data-imgurl="/data/project/1797/images/about-03.jpg"
                                                            title="Delete"><span class="glyphicon glyphicon-remove"
                                                                                 aria-hidden="true"></span></button>

                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);">
                                                        <img src="/data/project/1797/images/about-05.jpg" alt=""
                                                             width="75" height="45"/>
                                                    </a>
                                                    <button class="delete-about"
                                                            data-imgurl="/data/project/1797/images/about-05.jpg"
                                                            title="Delete"><span class="glyphicon glyphicon-remove"
                                                                                 aria-hidden="true"></span></button>

                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);">
                                                        <img src="/data/project/1797/images/about-06.jpg" alt=""
                                                             width="75" height="45"/>
                                                    </a>
                                                    <button class="delete-about"
                                                            data-imgurl="/data/project/1797/images/about-06.jpg"
                                                            title="Delete"><span class="glyphicon glyphicon-remove"
                                                                                 aria-hidden="true"></span></button>

                                                </li>

                                            </ul>


                                        </div>

                                        <br><br>

                                        <!--
                                        Integrate this one with WYSIHTML5 from x-editable
                                        -->


                                        <p>Invest in mining facilities in the Netherlands</p>

                                        <p>

                                            Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo
                                            ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis
                                            parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec,
                                            pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec
                                            pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo,
                                            rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede
                                            mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper
                                            nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu,
                                            consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra
                                            quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet.
                                            Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur
                                            ullamcorper ultricies nisi. Nam eget dui.

                                        </p>

                                    </div>

                                </div>


                                <div class='col-md-4'>
                                    <section class='white-panel-widget fundraising'>
                                        <h3>Summary</h3>

                                        <span class="survey-progress">
        <span class="survey-progress-bg">
          <span class="survey-progress-fg" style="width: 58.9642857143%;"></span></span>


                                            <br><br>

										<div class='row'>
											<div class='col-6 f300'>Token ERC20</div>
											<div class='col-6'>MINE (MINE)</div>
										</div>
                                            <div class='row'>
											<div class='col-md-6 f300'>Hardcap</div>
											<div class='col-md-6'>&euro; 1.400.000,00</div>
										</div>
                                            <div class='row'>
											<div class='col-md-6 f300'>Softcap</div>
											<div class='col-md-6'>&euro; 1.400.000,00</div>
										</div>

										<div class='row'>
											<div class='col-md-6 f300'>At the moment</div>
											<div class='col-md-6'>&euro; 825.500,00</div>
										</div>


										<div class='row'>
											<div class='col-md-6 f300'>Classification</div>
											<div class='col-md-6 green'>B</div>
										</div>

										<div class='row'>
											<div class='col-md-6 f300'>Projected profit</div>
											<div class='col-md-6'>6 %</div>
										</div>

										<br>

										<div class='row'>
											<div class='col-md-6 f300'>Turnover</div>
											<div class='col-md-6'>&euro; 3.520.000,00</div>
										</div>
										<div class='row'>
											<div class='col-md-6 f300'>Result</div>
											<div class='col-md-6'>&euro; 810.000,00</div>
										</div>
										<div class='row'>
											<div class='col-md-6 f300'>Valuation</div>
											<div class='col-md-6'>&euro; 10.000.000,00</div>
										</div>




										<br><br>

										<script type="text/javascript">
											function showDiv(toggle) {
                                                if (document.getElementById(toggle).style.display !== "none") {
                                                    document.getElementById(toggle).style.display = 'none';
                                                }
                                                else {
                                                    document.getElementById(toggle).style.display = 'block';
                                                }
                                            }

                                            $(document).ready(function () {
                                                $('#invest-success').hide();

                                                $('#invest_button').on('click', function () {
                                                    $('#invest_button').hide();
                                                });

                                                $("#invest-btn").on('click', function () {
                                                    var amount = parseFloat($("#invest-amount").val());


                                                    if (!$.isNumeric(amount))
                                                        return;

                                                    $.post('/userpanel/userprojects/' + 1797 + '/invest', {
                                                        user_id: '',
                                                        amount: amount
                                                    }).done(function (data) {
                                                        switch (data.status) {
                                                            case 'success':
                                                                $("#invest-amount").val('');
                                                                $('#invest-success').show();
                                                                break;
                                                        }

                                                    });

                                                });
                                            });

										</script>


                                            <!--<button class='btn btn-more' name="answer" onclick="showDiv('toggle')" id='invest_button'>Invest</button>-->

                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                <button type="button" class="btn btn-more-sm">Invest</button>
  <button type="button" class="btn btn-more-sm">Reserv</button>
                                            </div>


<section id="toggle" style="display:none; ">
											<form class="form-inline niceforms">
												<div class="form-group input-group">
													<label class="sr-only" for="invest-amount">Name</label>
													<span class='input-group-addon'>&euro;</span>
													<input type="text" class="form-control input-md" id="invest-amount"
                                                           placeholder="Amount">

												<span class="input-group-btn">
												<button type="button" id="invest-btn" class="btn btn-primary btn-md"
                                                        style='padding: 8px;'>Invest</button>
							</span>
												</div>
											</form>
											<p id="invest-success" class="bg-success">Thanks for your investments</p>
										</section>


                                    </section>


                                    <br>


                                    <section class='white-panel-widget fundraising'>
                                        <h3>Company</h3>

                                        <div class='row'>
                                            <div class='col-md-6 f300'>Name</div>
                                            <div class='col-md-6'></div>
                                        </div>


                                        <div class='row'>
                                            <div class='col-md-6 f300'>Telephone</div>
                                            <div class='col-md-6'></div>
                                        </div>

                                        <div class='row'>
                                            <div class='col-md-6 f300'>Email</div>
                                            <div class='col-md-6'></div>
                                        </div>

                                        <div class='row'>
                                            <div class='col-md-6 f300'>Place</div>
                                            <div class='col-md-6'>Eindhoven</div>
                                        </div>

                                        <div class='row'>
                                            <div class='col-md-6 f300'>Age</div>
                                            <div class='col-md-6 green'>> 3 year</div>
                                        </div>

                                        <div class='row'>
                                            <div class='col-md-6 f300'>Branch</div>
                                            <div class='col-md-6'>Mining</div>
                                        </div>

                                        <div class='row'>
                                            <div class='col-md-6 f300'>Employees (incl. team)</div>
                                            <div class='col-md-6'>4</div>
                                        </div>

                                        <div class='row'>
                                            <div class='col-md-6 f300'>Established</div>
                                            <div class='col-md-6'>2015</div>
                                        </div>


                                    </section>

                                    <br>

                                    <section class='white-panel-widget fundraising'>
                                        <h3>Tags</h3>
                                        <style>
                                            a.tags {
                                                display: inline-block;
                                                max-width: 100px;
                                                height: 30px;
                                                line-height: 28px;
                                                padding: 0 1em;
                                                background-color: #fff;
                                                border: 1px solid #eeeeee;
                                                border-radius: 0px;
                                                white-space: nowrap;
                                                text-overflow: ellipsis;
                                                overflow: hidden;
                                                color: #111;
                                                font-size: 13px;
                                                text-decoration: none;
                                                -webkit-transition: .2s;
                                                transition: .2s;
                                            }

                                            a.tags:hover {
                                                background-color: #00baf0;
                                                border: 1px solid #00baf0;
                                                color: #fff;
                                            }
                                        </style>

                                        <p><a href='#mining' class='black1 tags'>mining</a> <a href='#crypto'
                                                                                               class='black1 tags'>crypto</a>
                                        </p>

                                    </section>


                                    <style>
                                        .followers img {
                                            height: 48px;
                                            width: 48px;
                                            padding: 2px
                                        }
                                    </style>

                                    <section class='followers'>
                                        <h3 class='grey f14'>Community</h3>
                                    </section>


                                </div>
                            </div>


                            <!--
                            <div class='container'>
                                <div class='white-panel-widget'>
                                    <h3>Bedrijfsgegevens</h3>
                                    <p>
                                        <b>Naam:</b> <i>Alleen zichtbare voor geauditeerde investeerders</i><br>
                                        <b>Telefoon:</b>

                                                <i>Alleen zichtbare voor geauditeerde investeerders</i>
                                                <br>

                                        <b>Email:</b>

                                                <i>Alleen zichtbare voor geauditeerde investeerders</i>
                                                <br><br>

                                        <b>Plaats:</b>
                                                                                Sterksel<br>

                                        <br>
                                        <b>Stadium</b>
                                                                                <br>

                                        <b>Branche:</b>
                                                                                Industrie<br>

                                        <b>Werknemers:</b>
                                                                                 4<br>

                                        <b>Opgericht:</b>
                                                                                2014<br>

                                        <b>Website:</b>

                                                <i>Alleen zichtbare voor geauditeerde investeerders</i>
                                                <br><br>
                                                                        </p>


                                </div>

                                <br>


                                <div class='white-panel-widget'>
                                    <h3>Financial details</h3>
                                    <p>

                                    <b>Investment Needed for:</b>
                                                                                Business acquisition<br>

                                        <b>Turnover (2013):</b>
                                                                                0,00<br>

                                        <b>Profit (2013):</b>
                                                                                0,00<br>

                                        <b>Turnover (2014):</b>
                                                                                <br>

                                        <b>Profit (2014):</b>
                                                                                <br>

                                        <b>Omzet (2015):</b><br>
                                        <b>Winst (2015):</b><br>
                                        <b>Omzet (2016 - begroot):</b> &euro; 3.520.000,- <br>
                                        <b>Winst (2016 - begroot):</b> &euro; 810.000,- <br>
                                        <br><br>

                                        <b>Bedrijfswaardering:</b> &euro; 10.000.000,- euro.
                                        <br>
                                        <b>Reeds gefinancieerd:</b> &euro; <br>


                                        <b>Benodigde financiering:</b>
                                                                                1.400.000,00<br>

                                        <b>Opgehaald:</b>
                                                                                825.500,00<br>
                                                                        </p>
                                </div>

                                <br>

                                <script>
                                    var FounderController = {

                                        errorBlockSelectors : {
                                            title : '#founder-role-title'
                                        },

                                        addNew:function() {

                                            this.clearErrors();


                                            $.when(this.sendForm()).done($.proxy(function( response ) {

                                                if(typeof response.status == 'undefined')
                                                    return;

                                                switch(response.status){
                                                    case 'success':
                                                        this.clearErrors();
                                                        this.clearInput();
                                                        break;

                                                    case 'error':
                                                        if(typeof response.msgs == 'undefined')
                                                            return;

                                                        for(var field in response.msgs) {
                                                            if ( ! response.msgs.hasOwnProperty(field))
                                                                continue;

                                                            if(typeof this.errorBlockSelectors[field] == 'undefined')
                                                                continue;


                                                            $(this.errorBlockSelectors[field]).addClass('has-error');
                                                            $(this.errorBlockSelectors[field]+' .help-block').append(response.msgs[field]);
                                                        }

                                                        break;
                                                }
                                            }, this));
                                        },

                                        sendForm: function() {

                                            var data = this.getFormData();

                                            data['user_id'] = ;

                                            return $.ajax({
                                                method: "POST",
                                                url: App.siteURL + '/userpanel/userprojects/'+ 1797 +'/project/addroletitle',
                                                data: data
                                            });
                                        },

                                        getFormData: function() {
                                            var title = $( "#founder-role-input").val();

                                            return {
                                                title: title
                                            };
                                        },

                                        clearErrors: function(){
                                            for(var field in this.errorBlockSelectors) {
                                                if ( ! this.errorBlockSelectors.hasOwnProperty(field))
                                                    continue;

                                                $(this.errorBlockSelectors[field]).removeClass('has-error');
                                                $(this.errorBlockSelectors[field]+' .help-block').empty();
                                            }
                                        },

                                        clearInput : function() {
                                            $( "#founder-role-input").val('');
                                        }
                                    };

                                    $( document ).ready(function() {

                                        var autocompleteFounderRequest = '';

                                        $( "#founder-role-input" ).autocomplete({
                                            minLength: 0,
                                            source: function( request, response ) {

                                                autocompleteFounderRequest = request.term;

                                                $.ajax({
                                                    url: App.siteURL + '/userpanel/userprojects/'+ 1797 +'/founder/autocomplete',
                                                    data: {
                                                        q: autocompleteFounderRequest
                                                    },
                                                    success: function( data ) {

                                                        var pattern = new RegExp(autocompleteFounderRequest, "gi");

                                                        var values = $.map(data, function(item) {

                                                            item['label'] = item.title.replace(pattern,'<strong>$&</strong>');

                                                            return item;
                                                        });

                                                        values.push({
                                                            label: 'Add new: "<strong>' + request.term + '</strong>"',
                                                            title: request.term
                                                        });

                                                        response(values);

                                                    }
                                                });
                                            },
                                            focus: function( event, ui ) {
                                                $( "#founder-role-input" ).val( ui.item.title );
                                                return false;
                                            },
                                            select: function( event, ui ) {

                                                $( "#founder-role-input" ).val( ui.item.title );

                                                if(typeof ui.item.id != 'undefined'){
                                                    FounderController.clearInput();
                                                } else{
                                                    // add new RoleTitle
                                                    FounderController.addNew();
                                                }

                                                return false;
                                            }
                                        }).autocomplete( "instance" )._renderItem = function( ul, item ) {

                                            var $htmlItem = $('<p>');

                                            $htmlItem.append('<a>' + item.label + '</a>');

                                            return $( "<li>" )
                                                    .append( $htmlItem )
                                                    .appendTo( ul );
                                        };
                                    });
                                </script>
                                -->
                            <!--
                            <div class='row white-panel-widget'>
                                <h3>Founders</h3>
                                <div class="well well-sm">
                                    <hr>
                                    <form class="form-horizontal">
                                        <div id="founder-role-title" class="form-group">
                                            <label for="founder-role-input" class="col-sm-2 control-label">Add Tech Tags</label>
                                            <div class="col-sm-10">
                                                <input id="founder-role-input" type="text" class="form-control" placeholder="Add Tech Tags">

                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                    </form>
                                </div>


                                <div class="col-sm-2">
                                    <div class="thumbnail">
                                        <a href="http://biq.local:8888/u/cvsteenbergen" class=""><img class="img-rounded img-responsive" alt="cvsteenbergen" src="http://biq.local:8888/data/user/83/orig-6759276b00eeddc26d1fb79cccb1f3bba539c5b1.jpg" /></a>
                                        <div class="caption">
                                            <p><a href="http://biq.local:8888/u/cvsteenbergen" class="">cvsteenbergen</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            -->

                            <br>


                            <div class='white-panel-widget'>
                                <h3>Investment Highlights</h3>
                                <ul class='ugc'>
                                    <li>Highlight 1</li>
                                    <li>Highlight 2</li>
                                    <li>Highlight 3</li>
                                </ul>


                            </div>

                            <br>

                            <div class='white-panel-widget'>
                                <h3>SWOT Analyze</h3>
                                <div class='row'>
                                    <div class='col-md-6'><h4 class='blue'>Strong points</h4>
                                        <ul class='ugc'>
                                            <li>My strong points</li>

                                        </ul>
                                    </div>
                                    <div class='col-md-6'><h4 class='blue'>Weakness</h4>
                                        <ul class='ugc'>
                                            <li>My weaknesses</li>

                                        </ul>
                                    </div>

                                </div>
                                <div class='row'>
                                    <div class='col-md-6'><h4 class='blue'>Oppertunity</h4>
                                        <ul class='ugc'>
                                            <li>My changes in the market</li>
                                        </ul>

                                    </div>
                                    <div class='col-md-6'><h4 class='blue'>Threats</h4>
                                        <ul class='ugc'>
                                            <li>What could damage my business</li>
                                        </ul>
                                    </div>

                                </div>


                            </div>

                            <br>

                            <div class='white-panel-widget'>
                                <h3>Security, return and exit strategy</h3>

                                <h4 class='roze'>Securities</h4>
                                <ul class='ugc'>
                                    <li>This my team will bring in</li>

                                </ul>

                                <h4 class='roze'>Profits</h4>
                                <ul>
                                    <li>6% on a year base on the token value</li>
                                    <li></li>
                                </ul>


                                <h4 class='roze'>Exit</h4>
                                <ul class='ugc'>
                                    <li>Double payment when you hold the token for a long term</li>
                                </ul>
                            </div>


                            <br>

                            <!--
                            <div class='row white-panel-widget'>
                                <h3>Progress</h3>
                                                                    <span class="survey-progress">
        <span class="survey-progress-bg">
          <span class="survey-progress-fg" style="width: 58.9642857143%;"></span></span>

                                    <br><br>


                                <center><span >&euro;.  825.500,00 </span> <span style='color: #999'> van &euro;. 1.400.000,00 </span>
                                </span>
                                </center>

                                <table class="table">
                                                                    </table>
                            </div>

                            <br>
                            -->


                            <div class='white-panel-widget'>
                                <h3>Team</h3>

                                <script>
                                    $(function () {

                                        //Controllers
                                        var TeamController = {

                                            addFormSelector: '#add-form',

                                            errorBlockSelectors: {
                                                email: '#email-form-group',
                                                partnerType: '#partner-type-form-group',
                                                username: '#username-form-group',
                                                user_id: '#username-form-group'
                                            },

                                            clickAdd: function () {

                                                this.clearErrors();


                                                $.when(this.sendForm()).done($.proxy(function (response) {

                                                    if (typeof response.status == 'undefined')
                                                        return;

                                                    switch (response.status) {
                                                        case 'success':
                                                            this.clearErrors();
                                                            this.clearPartnerInput();
                                                            this.hideAddForm();

                                                            if (typeof response.usercard != 'undefined') {
                                                                $('#team-member-list').append(response.usercard);
                                                            }
                                                            break;

                                                        case 'error':
                                                            if (typeof response.msgs == 'undefined')
                                                                return;

                                                            for (var field in response.msgs) {
                                                                if (!response.msgs.hasOwnProperty(field))
                                                                    continue;

                                                                if (typeof this.errorBlockSelectors[field] == 'undefined')
                                                                    continue;


                                                                $(this.errorBlockSelectors[field]).addClass('has-error');
                                                                $(this.errorBlockSelectors[field] + ' .help-block').append(response.msgs[field]);
                                                            }

                                                            break;
                                                    }
                                                }, this));
                                            },

                                            clickCancel: function () {
                                                this.clearErrors();
                                                this.clearPartnerInput();
                                                this.hideAddForm();
                                            },

                                            sendForm: function () {

                                                var data = this.getFormData();

                                                data['user_id'] =;

                                                return $.ajax({
                                                    method: "POST",
                                                    url: App.siteURL + '/userpanel/userprojects/' + 1797 + '/project/addnewmember',
                                                    data: data
                                                });
                                            },

                                            getFormData: function () {
                                                var fullname = $("#partner-input").val();
                                                var username = (fullname.toLowerCase()).replace(/[^\w\d]/gi, '');
                                                var partnerType = $("#partner-type").val();
                                                var email = $("#partner-email").val();

                                                return {
                                                    fullname: fullname,
                                                    username: username,
                                                    partnerType: partnerType,
                                                    email: email
                                                };
                                            },

                                            clearErrors: function () {
                                                for (var field in this.errorBlockSelectors) {
                                                    if (!this.errorBlockSelectors.hasOwnProperty(field))
                                                        continue;

                                                    $(this.errorBlockSelectors[field]).removeClass('has-error');
                                                    $(this.errorBlockSelectors[field] + ' .help-block').empty();
                                                }
                                            },

                                            showAddForm: function () {
                                                $(this.addFormSelector).show();
                                            },

                                            hideAddForm: function () {
                                                $(this.addFormSelector).hide();
                                            },

                                            clearPartnerInput: function () {
                                                $("#partner-input").val('');
                                            }
                                        };


                                        var autocompleteRequest = '';

                                        $("#partner-input").autocomplete({
                                            minLength: 0,
                                            source: function (request, response) {

                                                autocompleteRequest = request.term;

                                                $.ajax({
                                                    url: App.siteURL + '/userpanel/userprojects/' + 1797 + '/team/autocomplete',
                                                    data: {
                                                        q: autocompleteRequest
                                                    },
                                                    success: function (data) {

                                                        var pattern = new RegExp(autocompleteRequest, "gi");

                                                        var values = $.map(data, function (item) {

                                                            item['label'] = item.fullname.replace(pattern, '<strong>$&</strong>');

                                                            return item;
                                                        });

                                                        values.push({
                                                            label: 'Add new: "<strong>' + request.term + '</strong>"',
                                                            fullname: request.term
                                                        });

                                                        response(values);

                                                    }
                                                });
                                            },
                                            focus: function (event, ui) {
                                                $("#partner-input").val(ui.item.fullname);
                                                return false;
                                            },
                                            select: function (event, ui) {

                                                $("#partner-input").val(ui.item.fullname);

                                                if (typeof ui.item.id != 'undefined') {

                                                    // user exist

                                                    TeamController.clearErrors();
                                                    TeamController.hideAddForm();

                                                    $.ajax({
                                                        method: "POST",
                                                        url: App.siteURL + '/userpanel/userprojects/' + 1797 + '/project/addmember',
                                                        data: {
                                                            user_id:,
                                                            member_id: ui.item.id
                                                        }
                                                    })
                                                        .done(function (response) {

                                                            if (typeof response.status == 'undefined')
                                                                return;

                                                            switch (response.status) {
                                                                case 'success':
                                                                    TeamController.clearPartnerInput();

                                                                    if (typeof response.usercard != 'undefined') {
                                                                        $('#team-member-list').append(response.usercard);
                                                                    }
                                                                    break;

                                                                case 'error':
                                                                    //$form.addClass('has-error');
                                                                    //$error.append(response.msgs);
                                                                    break;
                                                            }
                                                        });
                                                } else {

                                                    // user not exist


                                                    // show form
                                                    TeamController.showAddForm();
                                                }

                                                // debug
                                                var jsonPretty = JSON.stringify(ui.item, null, '\t');
                                                $("#partner-debug").html(jsonPretty);

                                                return false;
                                            }
                                        })
                                            .autocomplete("instance")._renderItem = function (ul, item) {

                                            var $htmlItem = $('<p>');

                                            $htmlItem.append('<a>' + item.label + '</a>');

                                            if (typeof item.ava != 'undefined')
                                                $htmlItem.prepend('<img src="' + item.ava + '" width="30" height="30">');

                                            return $("<li>")
                                                .append($htmlItem)
                                                .appendTo(ul);
                                        };

                                        //EVENTS
                                        $('#partner-btn-add').on('click', function () {
                                            TeamController.clickAdd();
                                        });

                                        $('#partner-btn-cancel').on('click', function () {
                                            TeamController.clickCancel();
                                        });

                                    });
                                </script>


                                <i>Only visible after registration</i>
                                <br><br>


                            </div>

                            <br>

                            <!--
                            <div class='white-panel-widget'>
                                <h3>Commentaar</h3>
                                <section id='comments'>
                                                                    </section>

                                <div>
                                    <section>
                                        <h3 class='title'>Heeft u vragen en/of opmerkingen, plaats dan uw naam en aanvulling hieronder in.</h3>
                                        <form action='http://biq.local:8888/userpanel/userprojects/1797/comment' method='post'>
                                            <div class='form-group'>
                                                <input name='name' class='form-control' type='text' placeholder="Uw naam">
                                            </div>
                                            <div class='form-group'>
                                                <textarea name='content' class='form-control' placeholder="Plaats hier uw opmerking, aanmerking of commentaar."></textarea>
                                            </div>
                                            <input type='submit' class='btn btn-primary'>
                                        </form>
                                    </section>
                                </div>
                            </div>

                            <br>
                            -->


                            <div class='white-panel-widget'>
                                <h3>More information</h3>

                                <h4>Files</h4>


                                <i>Only visible after registration</i>


                                <h4>Sources</h4>
                                <div id="more-links-container">

                                    <i>Only visible after registration</i>


                                </div>

                            </div>
                            <BR><BR>
                        </div>

                    </div>
                </div>


            </div>
        </div>


        </div> <!-- /#wrap -->

        <style>
            .liked, .followed {
                background-color: #fff;
                border-color: #ccc;
                box-shadow: none;
                opacity: .65;
            }
        </style>

        <script>
            var FollowBtnController = function (element) {
                this.el = element;

                this.lang = 'en';

                this.langLib = {
                    'en': {
                        'follow': 'Follow',
                        'followed': 'Followed'
                    }
                };
            };

            FollowBtnController.prototype = {
                click: function () {

                    this.disable();

                    $.when(this.post()).then($.proxy(function (data, textStatus, jqXHR) {

                        switch (data.status) {
                            case 'success':
                                this.onPostSuccess(data);

                                break;

                            default:
                                this.onPostFail(data);
                                break;
                        }

                        this.able();

                    }, this));
                },

                onPostSuccess: function (data) {
                    if (data.follower_status == '1') {
                        this.follow();
                    } else {
                        this.unfollow();
                    }
                },

                onPostFail: function (data) {

                },

                post: function () {
                    return $.post('/userpanel/userprojects/follow', {
                        user_id: '',
                        project_id: '1797'
                    });
                },

                follow: function () {
                    $(this.getElement()).addClass('followed');
                    this.clearTexts();

                    this.insertText('&nbsp;' + this.trans('followed'));
                },

                unfollow: function () {
                    $(this.getElement()).removeClass('followed');
                    this.clearTexts();

                    this.insertText('&nbsp;' + this.trans('follow'));
                },

                disable: function () {
                    $(this.getElement()).addClass('disabled');
                },

                able: function () {
                    $(this.getElement()).removeClass('disabled');
                },

                trans: function (key) {
                    return this.langLib[this.lang][key];
                },

                clearTexts: function () {
                    $(this.getElement()).contents().filter(function () {
                        return this.nodeType === 3;
                    }).remove();
                },

                insertText: function (text) {
                    $(this.getElement()).append(text);
                },

                getElement: function () {
                    return this.el;
                }
            };

            var LikeBtnController = function (element) {
                this.el = element;

                this.lang = 'en';

                this.langLib = {
                    'en': {
                        'like': 'Like',
                        'liked': 'Liked'
                    }
                };
            };

            LikeBtnController.prototype = {
                click: function () {

                    this.disable();

                    $.when(this.post()).then($.proxy(function (data, textStatus, jqXHR) {

                        switch (data.status) {
                            case 'success':
                                this.onPostSuccess(data);

                                break;

                            default:
                                this.onPostFail(data);
                                break;
                        }

                        this.able();

                    }, this));
                },

                post: function () {
                    return $.post('/userpanel/userprojects/like', {
                        user_id: '',
                        project_id: '1797'
                    });
                },

                onPostSuccess: function (data) {
                    if (data.liker_status == '1') {
                        this.like();
                    } else {
                        this.unlike();
                    }
                },

                onPostFail: function (data) {

                },

                toggleLike: function () {
                    if (this.isLiked()) {
                        this.unlike();
                    } else {
                        this.like();
                    }
                },

                toggleDisabled: function () {
                    if (this.isBtnDisabled()) {
                        this.able();
                    } else {
                        this.disable();
                    }
                },

                like: function () {
                    $(this.getElement()).addClass('liked');

                    this.clearTexts();

                    this.insertText('&nbsp;' + this.trans('liked'));
                },

                unlike: function () {
                    $(this.getElement()).removeClass('liked');

                    this.clearTexts();

                    this.insertText('&nbsp;' + this.trans('like'));
                },

                isLiked: function () {
                    return !!$(this.getElement()).hasClass('liked');
                },

                disable: function () {
                    $(this.getElement()).addClass('disabled');
                },

                able: function () {
                    $(this.getElement()).removeClass('disabled');
                },

                isBtnDisabled: function () {
                    return !!$(this.getElement()).hasClass('disabled');
                },

                trans: function (key) {
                    return this.langLib[this.lang][key];
                },

                clearTexts: function () {
                    $(this.getElement()).contents().filter(function () {
                        return this.nodeType === 3;
                    }).remove();
                },

                insertText: function (text) {
                    $(this.getElement()).append(text);
                },

                getElement: function () {
                    return this.el;
                }
            };

            var GalleryController = {

                $fullPreview: $('a.about-full-preview img'),

                init: function () {
                    $('body').on('click', 'ul.about-gallery-previews > li > a', function () {
                        var imgEl = $(this).find('img');
                        GalleryController.$fullPreview.attr('src', imgEl.attr('src'));
                    });

                    $('body').on('click', 'ul.about-gallery-previews > li > button.delete-about', function () {
                        if (!confirm("Are you sure?")) {
                            return false;
                        }

                        var $el = $(this);

                        var imgUrl = $(this).data('imgurl');

                        var filename = imgUrl.substring(imgUrl.lastIndexOf('/') + 1);

                        $.post('/userpanel/userprojects/' + 1797 + '/image/remove', {
                            user_id: '',
                            filename: filename
                        }).done(function (data) {
                            switch (data.status) {
                                case 'success':
                                    var $elem = $el.parent('li');

                                    $elem.hide();
                                    break;
                            }

                        });
                    });

                }

            };

            $(document).ready(function () {
                var likeBtn = new LikeBtnController($('a.like-btn').get(0));
                var followBtn = new FollowBtnController($('a.follow-btn').get(0));
                $(likeBtn.getElement()).on('click', function () {
                    likeBtn.click.apply(likeBtn);
                    return false;
                });
                $(followBtn.getElement()).on('click', function () {
                    followBtn.click.apply(followBtn);
                    return false;
                });

                GalleryController.init();
            });


            // DROPZONE
            $(document).ready(function () {
                Dropzone.autoDiscover = false;

                var dropzoneSelector = '.about-gallery-dropzone';
                var dropzoneSelectorHeader = '.about-header-dropzone';
                var avatarDropzoneSelector = '.avatar-userprojects-dropzone';

                window.avatarDropzone = $(dropzoneSelector).dropzone({
                    init: function () {

                    },
                    'url': App.siteURL + '/userpanel/userprojects/' + 1797 + '/upload',
                    'clickable': [dropzoneSelector],
                    'previewsContainer': '.about-gallery-previews',
                    'previewTemplate': '<li><a href="javascript:void(0);"><img data-dz-thumbnail height="45" width="75" /></a></li>',
                    'filesizeBase': 1024,
                    'uploadMultiple': false,
                    //'maxFiles' : 1,
                    'acceptedFiles': 'image/jpeg,image/png',
                    'dictDefaultMessage': $(dropzoneSelector).data('dict-default-message')
                });

                window.avatarDropzone = $(dropzoneSelectorHeader).dropzone({
                    init: function () {

                    },
                    'url': App.siteURL + '/userpanel/userprojects/' + 1797 + '/upload/header',
                    'clickable': [dropzoneSelectorHeader],
                    'previewsContainer': '.about-header-previews',
                    'previewTemplate': '<li><a href="javascript:void(0);"><img data-dz-thumbnail height="45" width="75" /></a></li>',
                    'previewsContainer': '.about-header-previews',
                    'filesizeBase': 1024,
                    'uploadMultiple': false,
                    'maxFiles': 1,
                    'acceptedFiles': 'image/jpeg,image/png',
                    'dictDefaultMessage': $(dropzoneSelectorHeader).data('dict-default-message')
                });

                window.avatarDropzone = $(avatarDropzoneSelector).dropzone({
                    init: function () {

                    },
                    'url': App.siteURL + '/userpanel/userprojects/' + 1797 + '/upload/avatar',
                    'clickable': ['.dropzone', '#upload-avatar-btn'],
                    'filesizeBase': 1024,
                    'uploadMultiple': false,
                    'maxFiles': 1,
                    'acceptedFiles': 'image/gif,image/jpeg,image/png',
                    'dictDefaultMessage': $(avatarDropzoneSelector).data('dict-default-message')
                });


            });


        </script>

        <style>
            .about-gallery-dropzone {

            }

            .about-gallery-dropzone .dz-message {
                margin: 0;
            }

            .about-gallery-dropzone.dropzone {
                min-height: 0;
                padding: 0;
            }

            a.about-full-preview:after {
                content: "";
                display: block;
            }

            ul.about-gallery-previews {
                list-style: none;

            }

            ul.about-gallery-previews > li {
                position: relative;
                display: inline-block;
            }

            ul.about-gallery-previews > li > a {
                display: inline-block;
            }

            ul.about-gallery-previews > li > button {
                position: absolute;
                top: 3px;
                right: 3px;
                border: none;
                margin: 0;
                padding: 0 2px 2px 2px;
                background-color: #AAAAAA;
                border-radius: 3px;
                opacity: 0.5;
            }

            ul.about-gallery-previews > li > button:hover {
                opacity: 1;
            }

            ul.about-gallery-previews > li.add-image-placeholder {

            }

            ul.about-gallery-previews > li.add-image-placeholder > form {
                display: inline-block;
                margin: 0;
                width: 62px;
                height: 43px;
            }


        </style>


    </section>
@endsection

@section('scripts_footer')
    <script type="text/javascript" src="{{asset('js/community/project.js')}}"></script>
@endsection