<?xml version="1.0" encoding="UTF-8"?>

<component>
	<property name="ID" get="getID" set="setID" type="string" />
	<property name="UniqueID" get="getUniqueID" type="string" />
	<property name="Parent" get="getParent" type="object" />
	<property name="Children" get="getChildren" type="array" />
	<property name="Application" get="getApplication" type="object" />
	<property name="Module" get="getModule" type="object" />
	<property name="Page" get="getPage" type="object" />
	<property name="User" get="getUser" type="object" />
	<property name="Session" get="getSession" type="object" />
	<property name="Request" get="getRequest" type="object" />
	<property name="Definition" get="getDefinition" type="object" />
	<property name="Globalization" get="getGlobalization" type="object" />
	<property name="ServiceManager" get="getServiceManager" type="object" />
	<event name="OnDataBinding" />
</component>