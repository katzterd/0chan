{{/*
Default name
*/}}
{{- define "0chan.fullname" -}}
{{- $name := default .Chart.Name .Values.nameOverride -}}
{{- if contains $name .Release.Name -}}
{{- .Release.Name | trunc 26 | trimSuffix "-" -}}
{{- else -}}
{{- printf "%s-%s" .Release.Name $name | trunc 26 | trimSuffix "-" -}}
{{- end -}}
{{- end -}}

{{/*
Frontend default name
*/}}
{{- define "0chan.frontend.fullname" -}}
{{- printf "%s-frontend" .Release.Name -}}
{{- end -}}

{{/*
Backend default name
*/}}
{{- define "0chan.backend.fullname" -}}
{{- printf "%s-backend" .Release.Name -}}
{{- end -}}

{{/*
Storage default name
*/}}
{{- define "0chan.storage.fullname" -}}
{{- printf "%s-storage" .Release.Name -}}
{{- end -}}

{{/*
db default name
*/}}
{{- define "0chan.db.fullname" -}}
{{- printf "%s-db" .Release.Name -}}
{{- end -}}

{{/*
Cache default name
*/}}
{{- define "0chan.cache.fullname" -}}
{{- printf "%s-cache" .Release.Name -}}
{{- end -}}

{{/*
Torgate default name
*/}}
{{- define "0chan.torgate.fullname" -}}
{{- printf "%s-torgate" .Release.Name -}}
{{- end -}}

{{/*
I2Pgate default name
*/}}
{{- define "0chan.i2pgate.fullname" -}}
{{- printf "%s-i2pgate" .Release.Name -}}
{{- end -}}

{{/*
Yggdrasilgate default name
*/}}
{{- define "0chan.yggdrasilgate.fullname" -}}
{{- printf "%s-yggdrasilgate" .Release.Name -}}
{{- end -}}