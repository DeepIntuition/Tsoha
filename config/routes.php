<?php

  $routes->get('/sandbox', function() {
    HelloWorldController::sandbox();
  });

  $routes->get('/', function() {
    AlgorithmController::home();
  });

  $routes-> post('/algorithm/new', function(){
    AlgorithmController::store();
  });

  $routes-> get('/algorithm/modify/:id', function($id){
    AlgorithmController::edit($id);
  });

  $routes-> post('/algorithm/modify/:id', function($id){
    AlgorithmController::update($id);
  });

  $routes->get('/index', function() {
    AlgorithmController::index();
  });

  $routes->get('/tags/index', function() {
    TagController::index();
  });

  $routes->get('/tags/:id', function($id) {
    TagController::tag_show($id);
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

  $routes->post('/algorithm/:id/deletealgo', function($id){
    AlgorithmController::delete($id);
  });

  $routes->get('/login', function() {
  	UserController::login();
  });

  $routes->post('/login', function() {
    UserController::verify_user();
  });

  $routes->post('/logout', function() {
    UserController::logout();
  });

  $routes->get('/register', function() {
    UserController::register();
  });

  $routes->post('/register', function() {
    UserController::save_new_user();
  });
