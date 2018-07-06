<template>
  <div class="animated fadeIn">
    <div class="card-group mb-4">
      <div class="card">
        <div class="card-block">
          <div class="h1 text-muted text-right mb-2">
            <i class="icon-arrow-up text-green"></i>
          </div>
          <div class="h4 mb-0">$ 12.500</div>
          <small class="text-muted text-uppercase font-weight-bold">BTC</small>
          <div class="chart-wrapper">
            <canvas id="sparkline-chart-1" width="100" height="30"></canvas>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-block">
          <div class="h1 text-muted text-right mb-2">
            <i class="icon-arrow-down text-red"></i>
          </div>
          <div class="h4 mb-0">$ 0.01</div>
          <small class="text-muted text-uppercase font-weight-bold">SCOIN</small>
          <div class="chart-wrapper">
            <canvas id="sparkline-chart-2" width="100" height="30"></canvas>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-block">
          <div class="h1 text-muted text-right mb-2">
            <i class="icon-wallet"></i>
          </div>
          <div class="h4 mb-0">$ 12000,00</div>
          <small class="text-muted text-uppercase font-weight-bold">Balance</small>
          <div class="progress progress-xs mt-1 mb-0">
            <div class="progress-bar bg-warning" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-block">
          <div class="h1 text-muted text-right mb-2">
            <i class="icon-layers"></i>
          </div>
          <div class="h4 mb-0">12</div>
          <small class="text-muted text-uppercase font-weight-bold">Outstanding orders</small>
          <div class="progress progress-xs mt-1 mb-0">
            <div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-block">
          <div class="h1 text-muted text-right mb-2">
            <i class="icon-link"></i>
          </div>
          <div class="h4 mb-0">12</div>
          <small class="text-muted text-uppercase font-weight-bold">Referrals</small>
          <div class="progress progress-xs mt-1 mb-0">
            <div class="progress-bar bg-danger" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'dashboard'
}
</script>
