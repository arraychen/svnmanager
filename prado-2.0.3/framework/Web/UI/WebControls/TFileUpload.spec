<?xml version="1.0" encoding="UTF-8"?>

<component>
	<property name="LocalName" get="getLocalName" type="string" />
	<property name="FileName" get="getFileName" type="string" />
	<property name="FileSize" get="getFileSize" type="string" />
	<property name="FileType" get="getFileType" type="string" />
	<property name="UploadError" get="getUploadError" type="string" />
	<property name="Uploaded" get="getUploaded" type="string" />
	<property name="Columns" get="getColumns" set="setColumns" type="integer" default="0" />
	<property name="MaxFileSize" get="getMaxFileSize" set="setMaxFileSize" type="string" />
	<event name="OnFileUpload" />
	<event name="OnFileUploadFailed" />
</component>