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
{{- printf "%s-frontend" (include "0chan.fullname" .) -}}
{{- end -}}

{{/*
Backend default name
*/}}
{{- define "0chan.backend.fullname" -}}
{{- printf "%s-backend" (include "0chan.fullname" .) -}}
{{- end -}}

{{/*
Storage default name
*/}}
{{- define "0chan.storage.fullname" -}}
{{- printf "%s-storage" (include "0chan.fullname" .) -}}
{{- end -}}

{{/*
db default name
*/}}
{{- define "0chan.db.fullname" -}}
{{- printf "%s-db" (include "0chan.fullname" .) -}}
{{- end -}}

{{/*
Cache default name
*/}}
{{- define "0chan.cache.fullname" -}}
{{- printf "%s-cache" (include "0chan.fullname" .) -}}
{{- end -}}

{{/*
Torgate default name
*/}}
{{- define "0chan.torgate.fullname" -}}
{{- printf "%s-torgate" (include "0chan.fullname" .) -}}
{{- end -}}

{{/*
I2Pgate default name
*/}}
{{- define "0chan.i2pgate.fullname" -}}
{{- printf "%s-i2pgate" (include "0chan.fullname" .) -}}
{{- end -}}

{{/*
Yggdrasilgate default name
*/}}
{{- define "0chan.yggdrasilgate.fullname" -}}
{{- printf "%s-yggdrasilgate" (include "0chan.fullname" .) -}}
{{- end -}}