<?php

  $routes->get('/sandbox', function() {
    HelloWorldController::sandbox();
  });

  $routes->get('/', function() {
    AlgorithmController::home();
  });

  $routes->get('/index', function() {
    AlgorithmController::index();
  });

  $routes->get('/implementation_show', function() {
    AlgorithmController::implementation_show();
  });

  $routes->get('/implementation_modify', function() {
    AlgorithmController::implementation_modify();
  });

  $routes->get('/algorithm/new', function() {
    AlgorithmController::new();
  });

  $routes->get('/algorithm/:id', function($id) {
    AlgorithmController::algorithm_show($id);
  });

  $routes->get('/algorithm_list', function() {
    AlgorithmController::algorithm_list();
  });

  $routes->get('/algorithm_modify', function() {
    AlgorithmController::algorithm_modify();
  });

  $routes->get('/login', function() {
  	AlgorithmController::login();
  });
