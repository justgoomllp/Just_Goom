@extends('front.layouts.user')

@section('title', 'Visitor Analytics — Just Goom')
@section('page_title', 'Visitor Analytics')
@section('body_attrs', 'class="user-panel-body" data-page="analytics" data-title="Visitor Analytics"')

@section('content')
<div class="user-content">
      <div class="user-stat-row">
        <div class="user-stat-card green"><div class="user-stat-info"><h3>2,847</h3><span>Total Visitors</span></div><span class="user-stat-icon">👁</span></div>
        <div class="user-stat-card yellow"><div class="user-stat-info"><h3>342</h3><span>This Month</span></div><span class="user-stat-icon">📅</span></div>
        <div class="user-stat-card red"><div class="user-stat-info"><h3>28</h3><span>Today</span></div><span class="user-stat-icon">📍</span></div>
        <div class="user-stat-card grey"><div class="user-stat-info"><h3>68%</h3><span>Return Rate</span></div><span class="user-stat-icon">🔄</span></div>
      </div>
      <div class="user-panels-row">
        <div class="user-panel">
          <div class="user-panel-head">Recent Visitors</div>
          <div class="user-panel-body">
            <div class="user-list-item"><div><strong>Raj Kumar</strong><span>Ahmedabad · 2 hours ago</span></div></div>
            <div class="user-list-item"><div><strong>Priya Sharma</strong><span>Surat · 5 hours ago</span></div></div>
            <div class="user-list-item"><div><strong>Amit Mehta</strong><span>Vadodara · Yesterday</span></div></div>
            <div class="user-list-item"><div><strong>Nisha Kaur</strong><span>Rajkot · Yesterday</span></div></div>
          </div>
        </div>
        <div class="user-panel">
          <div class="user-panel-head">Visitor History (7 days)</div>
          <div class="user-panel-body">
            <div style="display:flex;align-items:flex-end;gap:8px;height:140px">
              <div style="flex:1;text-align:center"><div style="background:#5cb85c;height:40%;border-radius:3px 3px 0 0;margin-bottom:6px"></div><span style="font-size:11px">Mon 32</span></div>
              <div style="flex:1;text-align:center"><div style="background:#5cb85c;height:55%;border-radius:3px 3px 0 0;margin-bottom:6px"></div><span style="font-size:11px">Tue 44</span></div>
              <div style="flex:1;text-align:center"><div style="background:#5cb85c;height:70%;border-radius:3px 3px 0 0;margin-bottom:6px"></div><span style="font-size:11px">Wed 56</span></div>
              <div style="flex:1;text-align:center"><div style="background:#5cb85c;height:45%;border-radius:3px 3px 0 0;margin-bottom:6px"></div><span style="font-size:11px">Thu 36</span></div>
              <div style="flex:1;text-align:center"><div style="background:#f0ad4e;height:85%;border-radius:3px 3px 0 0;margin-bottom:6px"></div><span style="font-size:11px">Fri 68</span></div>
              <div style="flex:1;text-align:center"><div style="background:#5cb85c;height:60%;border-radius:3px 3px 0 0;margin-bottom:6px"></div><span style="font-size:11px">Sat 48</span></div>
              <div style="flex:1;text-align:center"><div style="background:#5cb85c;height:35%;border-radius:3px 3px 0 0;margin-bottom:6px"></div><span style="font-size:11px">Sun 28</span></div>
            </div>
          </div>
        </div>
      </div>
    </div>
@endsection
