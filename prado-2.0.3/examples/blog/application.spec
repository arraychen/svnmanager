<?xml version="1.0" encoding="UTF-8"?>
<!-- 
  application specification
  @ID (required): the ID of the application
  @state (default=on): the state of the application.
    If @state=on, the application is in production working.
    If @state=off, the application is in maintenance/stop state.
    If @state=debug, the application is in development state.
-->
<application ID="blog" state="debug">
  <!-- 
    request class (optional): the class encapsulating request info.
    @class (default=TRequest): the request class name.
    @default (when @class=TRequest, required): the default page name (may include module name).
    @format (when @class=TRequest, default=get): the format for parameter representation in URL.
      If @format=get, the URL looks like: /index.php?name1=value1&name2=value2
      If @format=path, the URL looks like: /index.php/name1/value1/name2/value2
  -->
  <request default="Blog:ViewAllPage" format="get" />
  <!-- 
    user class (optional): the class representing the user object.
      @class (required): the user class name (must implement IUser).
  -->
  <user class="BlogUser" />
  <!-- 
    session class (optional): the class responsible for session management.
    @class (default=TSession): the session class name (must implement ISession).
  -->
  <session enabled="true" />
  <!-- 
    cache manager class (optional): the class responsible for caching components (specs, templates)
    @class (default=TCacheManager): the cache manager class name.
    @enabled (when @class=TCacheManager, default=false): whether to use caching scheme.
    @path (when @class=TCacheManager, optional): the directory for saving cached data.
  -->
  <cache enabled="true" />
  <!-- 
    resource parser class (optional): the class responsible for parsing component specs and templates.
    @class (default=TResourceParser): the resource parser class name.
    <parser class="TResourceParser" />
  -->
  <!-- 
    resource locator class (optional): the class responsible for locating component specs and templates.
    @class (default=TResourceLocator): the resource locator class name.
    <locator class="TResourceLocator" />
  -->
  <!-- 
    viewstate manager class (optional): the class responsible for locating component specs and templates.
    @class (default=TViewStateManager): the resource locator class name.
    @enabled (default=false): whether to use viewstate manager.
    @buffer-size (default=10): how many page viewstates should be kept.
	@key (optional): if session is enabled and key is empty, an automatically generated key will be used.
	@encrypt (default=false): if true, the viewstate will be encrypted using DES.
	<vsmanager class="TViewStateManager" enabled="true" key="secret key" encrypt="false" />
  -->
  <!-- 
    error handling class (optional): the class responsible for handling errors.
    @class (default=TErrorHandler): the error handling class name.
  -->
  <error>
    <!--
      If @class=TErrorHandler, the following elements are acceptable within error element:
        <when error="ErrorName" page="PageName" /> : specify pages responsible for different errors.
        <otherwise page="PageName" /> : specify the page if the error is not recoganized.
    -->
    <when error="SiteOff" page="ErrorPage" />
    <when error="PageNotFound" page="ErrorPage" />
    <when error="Unauthorized" page="ErrorPage" />
    <when error="Forbidden" page="ErrorPage" />
    <when error="InternalError" page="ErrorPage" />
    <otherwise page="ErrorPage" />
  </error>

  <alias name="BlogApp" path="." />

  <using namespace="System.Web.UI.WebControls" />
    <using namespace="System.Security" />
    <using namespace="BlogApp.global" />

  <parameter name="DSN">sqlite://blog%2Fblog.db</parameter>

  <module ID="User" class="DataModule">
    <using namespace="BlogApp.UserModule" />
    <secured page="EditPage" />
    <parameter name="AllowNewAccount">true</parameter>
  </module>

  <module ID="Blog" class="DataModule">
    <using namespace="BlogApp.BlogModule" />
    <secured page="EditPage" />
    <secured page="NewPage" />
    <parameter name="AllowAllDelete">true</parameter>
  </module>

</application>