<?php

// Set service and resource data
$myNew          = new Service_MyNew();
$newsResource   = $myNew->news;

const CONTROLLER_NEW_FAILED_CREATE  = 1;
const CONTROLLER_NEW_FAILED_DELETE  = 2;
const CONTROLLER_NEW_FAILED_UPDATE  = 3;
const CONTROLLER_NEW_SUCCESS_CREATE = 4;
const CONTROLLER_NEW_SUCCESS_DELETE = 5;
const CONTROLLER_NEW_SUCCESS_UPDATE = 6;

$this->respond("GET", "/?", function ($request, $response, $service) use ($newsResource) {
    // List all news
    $news = $newsResource->listNews();
    
    // Header params
    $service->pageTitle = "App News";
    
    // Content params
    $service->news      = $news;
    
    // Render
    $service->render(__PATH__ . "/app/view/new/list.phtml");
});

$this->respond("GET", "/create/?", function ($request, $response, $service) {
    // Header params
    $service->pageTitle = "Create New";
    
    $service->render(__PATH__ . "/app/view/new/create.phtml");
});

$this->respond("GET", "/[i:newId]/?", function ($request, $response, $service) use ($newsResource) {
    $newId = $request->param("newId");
    
    // Get new
    $new = $newsResource->get($newId);
    
    // Header params
    $service->pageTitle = "Edit New";
    
    // Content params
    $service->new = $new;
    
    $service->render(__PATH__ . "/app/view/new/edit.phtml");
});


// Methods
$this->respond("POST", "/create/?", function ($request, $response, $service) use ($newsResource) {
    $txtTitle       = $request->param("txtTitle");
    $txtDate        = $request->param("txtDate", get_date());
    $cboStatus      = $request->param("cboStatus");
    $txtDescription = $request->param("txtDescription");
    
    // Create new object to register
    $new = new _New([
        "title"         => $txtTitle,
        "description"   => $txtDescription,
        "dateCreate"    => $txtDate,
        "status"        => $cboStatus
    ]);
    
    if($newsResource->insert($new)){
        $response->redirect("/new?err=" . CONTROLLER_NEW_SUCCESS_CREATE);
    }else{
        $response->redirect("/new?err=" . CONTROLLER_NEW_FAILED_CREATE);
    }
});

$this->respond("POST", "/[i:newId]/?", function ($request, $response, $service) use ($newsResource) {
    $newId          = $request->param("newId");
    $txtTitle       = $request->param("txtTitle");
    $txtDate        = $request->param("txtDate", get_date());
    $txtDescription = $request->param("txtDescription");
    
    $new = $newsResource->get($newId);
    
    $new->setTitle($txtTitle);
    $new->setDateCreate($txtDate);
    $new->setDescription($txtDescription);
    
    if($newsResource->update($new)){
        $response->redirect("/new?err=" . CONTROLLER_NEW_SUCCESS_UPDATE);
    }else{
        $response->redirect("/new?err=" . CONTROLLER_NEW_FAILED_UPDATE);
    }
});

$this->respond("GET", "/[i:newId]/delete/?", function ($request, $response, $service) use ($newsResource) {
    $newId = $request->param("newId");
    
    if($newsResource->delete($newId)){
        $response->redirect("/new?err=" . CONTROLLER_NEW_SUCCESS_DELETE);
    }else{
        $response->redirect("/new?err=" . CONTROLLER_NEW_FAILED_DELETE);
    }
});