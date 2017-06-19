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

  $routes->get('/algorithm/new', function() {
    AlgorithmController::new();
  });

  $routes->post('/algorithm/new', function(){
    AlgorithmController::store();
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

  $routes->get('/algorithm/deletealgo/:id', function($id){
    AlgorithmController::delete($id);
  });

  $routes->get('/algorithm/modify/:id', function($id){
    AlgorithmController::edit($id);
  });

  $routes-> post('/algorithm/modify/:id', function($id){
    AlgorithmController::update($id);
  });
  
  $routes-> get('/algorithm/:id/analysis/edit/:analysis_id', function($id, $analysis_id){
    AnalysisController::edit($id, $analysis_id);
  });

  $routes-> post('/algorithm/:id/analysis/edit/:analysis_id', function($id, $analysis_id){
    AnalysisController::update($id);
  });

  $routes-> post('/algorithm/:id/analysis/delete/:analysis_id', function($analysis_id, $algorithm_id){
    AnalysisController::delete($analysis_id, $algorithm_id);
  });

  $routes-> get('/algorithm/:id/analysis/new', function($id){
    AnalysisController::new($id);
  });

  $routes-> post('/algorithm/:id/analysis/new', function($id){
    AnalysisController::store($id);
  });

  $routes-> get('/algorithm/:algorithm_id/implementation/:planguage', function($algorithm_id, $planguage){
    ImplementationController::show($algorithm_id, $planguage);
  });

  $routes->get('/tags/index', function() {
    TagController::index();
  });

  $routes->get('/tags/:id', function($id) {
    TagController::tag_show($id);
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

  $routes->get('/logout', function() {
    UserController::logout();
  });

  $routes->get('/register', function() {
    UserController::register();
  });

  $routes->post('/register', function() {
    UserController::save_new_user();
  });

  $routes->get('/class/new', function() {
    AClassController::new();
  });

  $routes->post('/class/new', function() {
    AClassController::store();
  });

  
