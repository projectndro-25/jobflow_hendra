<?php
return [
  // publik
  ['GET','/',                 'HomeController@index'],
  ['GET','/jobs',             'JobController@publicIndex'],
  ['GET','/jobs/{slug}',      'JobController@show'],

  // publik apply list + form
  ['GET','/apply',            'ApplicationController@listPublic'],
  ['GET','/apply/{slug}',     'ApplicationController@createPublic'],
  ['POST','/apply/{slug}',    'ApplicationController@storePublic'],
  ['GET','/apply/thanks',     'ApplicationController@success'],

  // auth
  ['GET','/login',            'AuthController@loginForm'],
  ['POST','/login',           'AuthController@login'],
  ['GET','/logout',           'AuthController@logout'],

  // dashboard
  ['GET','/dashboard',                         'HomeController@dashboard'],

  // Jobs dashboard
  ['GET','/dashboard/jobs',                    'JobController@index'],
  ['GET','/dashboard/jobs/create',             'JobController@create'],
  ['POST','/dashboard/jobs',                   'JobController@store'],
  ['GET','/dashboard/jobs/{id}/edit',          'JobController@edit'],
  ['POST','/dashboard/jobs/{id}',              'JobController@update'],
  ['POST','/dashboard/jobs/{id}/toggle',       'JobController@toggle'],
  ['POST','/dashboard/jobs/{id}/delete',       'JobController@destroy'],

  // Applications (dashboard)
  ['GET','/dashboard/applications',            'ApplicationController@index'],
  ['GET','/dashboard/applications/{id}',       'ApplicationController@show'],
  ['POST','/dashboard/applications/{id}/email','ApplicationController@sendEmail'],

  // Pipeline board + AJAX
  ['GET','/dashboard/pipeline',                'PipelineController@board'],
  ['POST','/dashboard/pipeline/status',        'PipelineController@updateStatus'],

  // Schedules (buat jadwal)
  ['GET','/dashboard/schedules/create',        'ScheduleController@create'],
  ['POST','/dashboard/schedules',              'ScheduleController@store'],

  // Reports / Export
  ['GET','/dashboard/reports',                 'ReportController@index'],
  ['POST','/dashboard/export',                 'ExportController@csv'],
];
