<style>
    .progress-widget {
        margin: 20px auto 60px;
        padding: 21px 20px;

        -webkit-font-smoothing: antialiased;
        text-align: center;
    }

    .progress {
        color: #fff;
        min-width: 300px;
        max-width: 810px;
        margin: auto;
    }



    .progress:after {
        clear: both;
        display: table;
        content: '';
    }

    .progress-number {
        position: relative;
        letter-spacing: 0.4px;
    }

    .progress-number-label {
        display: block;
        font-size: 10px;
        font-weight: 700;
        margin-top: 2px;
        text-transform: uppercase;
        opacity: 0.7;
        color: #fff;
    }

    .progress-start {
        float: left;
        text-align: left;
        margin-top: 22px;
    }

    .progress-start img {
        margin-bottom: 5px;
    }

    .progress-number:after {
        position: absolute;
        left: 50%;
        margin-left: -1px;
        height: 10px;
        width: 1px;
    }

    .progress-current {
        margin: 0 0 33px;
        font-size: 18px;
        font-weight: 600;
        color: #ABDB00;
        letter-spacing: 0.48px;
        opacity: 0;
    }

    .progress-current:before {
        position: absolute;
        top: 105%;
        right: 0;
        left: 0;
        width: 4px;
        display: block;
        margin: auto;

        border-top-color: #ABDB00;
        content: '';
    }

    .progress-current:after {
        top: 100%;
        margin-top: 5px;
        height: 35px;
        background: #ABDB00;
        content: '';
    }

    .progress-target:after {
        top: -35px;
        background: #fff;
        height: 24px;
        content: '';
    }

    .progress-hard-target,
    .progress-target {
        float: right;
        margin: 27px 0 0;
        font-size: 14px;
    }

    .progress-bar {
        position: relative;
        box-shadow: 0 0 10px 0 rgba(0,46,103,0.33);
        background: #11A9D1;
        height: 8px;
    }

    .progress-bar:after {
        position: absolute;
        top: 0;
        left: 100%;
        bottom: -16px;
        width: 1px;
        background: #fff;
        content: '';
    }

    .progress-bar-current {
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        width: 65%;
        background-image: linear-gradient(-90deg, #DBFF58 3%, #ABDB00 87%);
    }

    .progress-participants {
        display: inline-block;
        margin: 35px 0 30px;
        padding: 4px 10px 2px;
        background: rgba(0,18,33,0.3);
        border-radius: 2px;
        border: 1px solid rgba(0,0,0,0.3);

        color: #fff;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 1px;
        line-height: 16px;
        text-transform: uppercase;
    }

    .progress-participants span {
        color: #ABDB00;
    }

    .founded-money {
        font-size: 18px;
        font-weight: 700;
        color: #fff;
    }

    .founded-money > * {
        display: inline-block;
        margin: 10px 15px;
    }

    .founded-money img {
        position: relative;
        top: -1px;
        margin-right: 7px;
        vertical-align: middle;
    }

    @media (max-width: 759px) {
        .progress-hard-target,
        .progress-target {
            text-align: right;
        }

        .progress-hard-target {
            margin-left: 25px;
        }

        .progress-hard-target span:first-child + span {
            display: none;
        }
    }

    @media (min-width: 760px) {
        .progress-number-label {
            font-size: 12px;
        }

        .progress-current {
            margin-bottom: 24px;
            font-size: 24px;
        }

        .progress-current:after {
            height: 26px;
        }

        .progress-hard-target,
        .progress-target {
            font-size: 20px;
            color: #fff;
        }

        .progress-hard-target {
            margin-left: 65px;
        }

        .progress-participants {
            margin: 35px 0 43px;
        }

        .progress-hard-target span:first-child {
            display: none;
        }

        .founded-money > * {
            margin: 0 32px;
        }

        .progress-start {
            margin-top: 37px;
        }

        .progress-start .progress-number-label {
            display: inline-block;
            margin: 2px 0 0 4px;
            vertical-align: top;

        }
    }
</style>
<div class="progress-widget">

        <div class="progress-number progress-current">
            <span>0</span>
            <span class="progress-number-label"></span>
        </div>

        <div class="progress-bar">
            <div class="progress-bar-current"></div>
        </div>

        <div class="progress-number progress-start">
            <span class="progress-number-label">Distribution Start - Total Supply: <b>120M</b></span>
        </div>

        <div class="progress-number progress-hard-target">
      <span>

      </span>
            <span>
        30 000 000 $
      </span>
            <span class="progress-number-label">Hard cap</span>
        </div>

        <div class="progress-number progress-target">
            10 000 000 $
            <span class="progress-number-label">
        Soft Cap
      </span>
        </div>


    <!--
    <div class="progress-participants">participants: <span></span></div>

    <div class="founded-money">
        <div class="founded-money-usd">
            <img src="new/img/progress/usd.svg" alt="">
            <span></span>
        </div>
        <div class="founded-money-btc">
            <img src="new/img/progress/btc.svg" alt="">
            <span></span>
        </div>
        <div class="founded-money-eth">
            <img src="new/img/progress/eth.png" alt="">
            <span></span>
        </div>
    </div>
    -->
</div>