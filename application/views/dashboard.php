<!-- Demo Dashboard -->
<div class="right_col" role="main">
<div class="page-title">
<h3>Dashboard</h3>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
.kpi-card{border-radius:12px;padding:20px;color:#fff;margin-bottom:20px;box-shadow:0 2px 8px rgba(0,0,0,.15)}
.kpi-card i{font-size:34px;float:right;opacity:.3}
.bg1{background:#3498db}.bg2{background:#27ae60}.bg3{background:#e67e22}.bg4{background:#8e44ad}
.panel-modern{background:#fff;border-radius:10px;padding:15px;box-shadow:0 2px 8px rgba(0,0,0,.08);margin-bottom:20px}
.quick-btn{display:block;padding:10px;margin:8px 0;border:1px solid #ddd;border-radius:6px;text-decoration:none}
</style>

<div class="row">
<?php
$cards=[
["bg1","fa-file-text","Total Enquiries","1245"],
["bg2","fa-edit","Quotations","980"],
["bg3","fa-shopping-cart","Sales Orders","756"],
["bg4","fa-money","Invoices","AED 3.85M"]
];
foreach($cards as $c){ ?>
<div class="col-md-3">
<div class="kpi-card <?=$c[0]?>">
<i class="fa <?=$c[1]?>"></i>
<h4><?=$c[2]?></h4>
<h2><?=$c[3]?></h2>
</div>
</div>
<?php } ?>
</div>

<div class="row">
<!-- <div class="x_panel">
    <div class="x_title">
        <h2>Monthly Sales</h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <canvas id="salesChart" height="100"></canvas>
    </div>
</div> -->
<!-- <div class="col-md-4">
<div class="panel-modern">
<h4>Sales by Currency</h4>
<ul>
<li>AED - 75%</li>
<li>USD - 20%</li>
<li>EUR - 5%</li>
</ul>
<img src="https://placehold.co/300x220?text=Doughnut+Chart" class="img-responsive">
</div>
</div> -->
</div>

<div class="row">
<div class="col-md-6">
<div class="panel-modern">
<h4>Recent Enquiries</h4>
<table class="table table-striped">
<thead><tr><th>Date</th><th>Customer</th><th>Status</th></tr></thead>
<tbody>
<tr><td>20-Jun</td><td>ABC LLC</td><td>New</td></tr>
<tr><td>20-Jun</td><td>XYZ Group</td><td>Quoted</td></tr>
<tr><td>19-Jun</td><td>Prime Foods</td><td>Follow Up</td></tr>
</tbody></table>
</div>
</div>

<div class="col-md-6">
<div class="panel-modern">
<h4>Pending Quotations</h4>
<table class="table table-bordered">
<thead><tr><th>No</th><th>Customer</th><th>Amount</th></tr></thead>
<tbody>
<tr><td>Q1001</td><td>ABC</td><td>12,500</td></tr>
<tr><td>Q1002</td><td>Noor</td><td>25,800</td></tr>
<tr><td>Q1003</td><td>Elite</td><td>9,750</td></tr>
</tbody></table>
</div>
</div>
</div>

<div class="row">
<div class="col-md-4">
<div class="panel-modern">
<h4>Quick Actions</h4>
<a href="#" class="quick-btn"><i class="fa fa-plus"></i> New Enquiry</a>
<a href="#" class="quick-btn"><i class="fa fa-edit"></i> New Quotation</a>
<a href="#" class="quick-btn"><i class="fa fa-shopping-cart"></i> Sales Order</a>
<a href="#" class="quick-btn"><i class="fa fa-file"></i> Invoice</a>
</div>
</div>

<div class="col-md-4">
<div class="panel-modern">
<h4>Top Customers</h4>
<table class="table">
<tr><td>ABC Trading</td><td>AED 450K</td></tr>
<tr><td>Prime Foods</td><td>AED 390K</td></tr>
<tr><td>Al Noor</td><td>AED 280K</td></tr>
</table>
</div>
</div>

<div class="col-md-4">
<div class="panel-modern">
<h4>Today's Activities</h4>
<ul>
<li>12 New Enquiries</li>
<li>8 Quotations</li>
<li>5 Sales Orders</li>
<li>4 Invoices</li>
<li>6 Payments</li>
</ul>
</div>
</div>
</div>

</div>



<script>
const ctx = document.getElementById('salesChart').getContext('2d');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug'],
        datasets: [{
            label: 'Sales (AED)',
            data: [120000,150000,180000,170000,220000,250000,240000,300000],
            backgroundColor: [
                '#3498db',
                '#2ecc71',
                '#f39c12',
                '#9b59b6',
                '#1abc9c',
                '#e74c3c',
                '#34495e',
                '#16a085'
            ]
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            }
        }
    }
});
</script>