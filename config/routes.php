<?php

  $routes->get('/', function() {
    HelloWorldController::index();
  });

  $routes->get('/implementation_show', function() {
    HelloWorldController::implementation_show();
  });

  $routes->get('/implementation_modify', function() {
    HelloWorldController::implementation_modify();
  });

  $routes->get('/algorithm_show', function() {
    HelloWorldController::algorithm_show();
  });

  $routes->get('/algorithm_list', function() {
    HelloWorldController::algorithm_list();
  });

  $routes->get('/algorithm_modify', function() {
    HelloWorldController::algorithm_modify();
  });

  $routes->get('/login', function() {
  	HelloWorldController::login();
  });
