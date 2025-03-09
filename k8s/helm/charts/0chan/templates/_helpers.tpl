{{/*
Registry override
*/}}
{{- define "ImageRegistry" -}}
{{- if .Values.registry }}
{{- .Values.registry }}
{{- else }}
{{- .Values.defaultRegistry }}
{{- end }}
{{- end }}

{{/*
Default secrets name override
*/}}
{{- define "SecretsName" -}}
{{- if .Values.secretsName }}
{{- .Values.secretsName }}
{{- else }}
{{- .Values.defaultSecretsName }}
{{- end }}
{{- end }}

{{/*
Image pull secrets
*/}}
{{- define "ImagePullSecret" -}}
{{- if .Values.imagePullSecretName }}
imagePullSecrets:
        - name: {{- .Values.imagePullSecretName }}
{{- else }}
{{- end }}
{{- end }}

{{/*
Default storageClass name override
*/}}
{{- define "StorageClassName" -}}
{{- if .Values.storageClass.name }}
{{- .Values.storageClass.name }}
{{- else }}
{{- .Values.storageClass.defaultName }}
{{- end }}
{{- end }}

{{/*
Postgres space request override
*/}}
{{- define "DbSpace" -}}
{{- if .Values.dbSpace }}
{{- .Values.dbSpace }}
{{- else }}
{{- .Values.storageClass.pods.db.defaultRequestSpace }}
{{- end }}
{{- end }}

{{/*
Storage space request override
*/}}
{{- define "StorageSpace" -}}
{{- if .Values.storageSpace }}
{{- .Values.storageSpace }}
{{- else }}
{{- .Values.storageClass.pods.storage.defaultRequestSpace }}
{{- end }}
{{- end }}
