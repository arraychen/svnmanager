<?xml version="1.0" encoding="UTF-8"?>

<component>
	<property name="DataSourceName" get="getDataSourceName" set="setDataSourceName" type="string" />
	<property name="Driver" get="getDriver" set="setDriver" type="string" default="mysql" />
	<property name="Host" get="getHost" set="setHost" type="string" />
	<property name="Username" get="getUsername" set="setUsername" type="string" />
	<property name="Password" get="getPassword" set="setPassword" type="string" />
	<property name="Database" get="getDatabase" set="setDatabase" type="string" />
	<property name="FetchMode" get="getFetchMode" set="setFetchMode" type="string" default="Associative" />
	<property name="PersistentConnection" get="isPersistentConnection" set="setPersistentConnection" type="boolean" default="true" />
	<property name="CacheDir" get="getCacheDir" set="setCacheDir" type="string" />
</component>