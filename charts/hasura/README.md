# Hasura Chart for Kubernetes

![Version: 3.0.0](https://img.shields.io/badge/Version-3.0.0-informational?style=flat-square) ![Type: application](https://img.shields.io/badge/Type-application-informational?style=flat-square) ![AppVersion: v2.34.0-ce](https://img.shields.io/badge/AppVersion-v2.34.0--ce-informational?style=flat-square)

A Helm chart to install Hasura graphql engine in a Kubernetes cluster.

[Learn more about Hasura.](https://hasura.io)

## Installing the Chart

To install the chart with the release name `my-release`, run the following commands:

    helm repo add hasura-extra https://hasura-extra.github.io/hasura-extra
    helm install my-release hasura-extra/hasura

## Values

| Key | Type | Default | Description |
|-----|------|---------|-------------|
| adminInternalErrors | string | `nil` | Include the internal key in the errors extensions of the response for GraphQL requests with the admin role (if required). |
| adminSecret | string | `"!ChangeMe!"` | Admin secret key, required to access this instance. This is mandatory when you use webhook or JWT. |
| affinity | object | `{}` | [Affinity](https://kubernetes.io/docs/concepts/scheduling-eviction/assign-pod-node/#affinity-and-anti-affinity) configuration. See the [API reference](https://kubernetes.io/docs/reference/kubernetes-api/workload-resources/pod-v1/#scheduling) for details. |
| asyncActionsFetchInterval | string | `nil` | Interval in milliseconds to sleep before trying to fetch async actions again after a fetch returned no async actions from metadata storage. Value 0 implies completely disable fetching async actions from the storage. |
| authHook | string | `nil` | URL of the authorization webhook required to authorize requests. See [auth webhooks docs](https://hasura.io/docs/latest/graphql/core/auth/authentication/webhook.html). |
| authHookMode | string | `"GET"` | HTTP method to use for the authorization webhook. |
| authHookSendRequestBody | string | `"true"` | Whether or not to send the request body (graphql request/variables) to the auth hook in POST mode. |
| autoscaling | object | Disabled by default. | Autoscaling by resources |
| closeWebsocketsOnMetadataChange | string | `"true"` | When metadata changes, close all WebSocket connections (with error code 1012). This is useful when you want to ensure that all clients reconnect to the latest metadata. |
| connectionCompression | string | `"false"` | Enable WebSocket permessage-deflate compression. |
| consoleAssetsDir | string | `nil` | Set the value to /srv/console-assets for the console to load assets from the server itself instead of CDN. |
| corsDomain | string | `nil` | CSV of list of domains, incuding scheme (http/https) and port, to allow for CORS. Wildcard domains are allowed. |
| dbUrl | string | `"!ChangeMe!"` | Postgres database URL. Example: postgres://admin:mypass@mydomain.com:5432/mydb |
| defaultNamingConvention | string | `"hasura-default"` | Used to set the default naming convention for all sources. See [naming convention](https://hasura.io/docs/latest/schema/postgres/naming-convention/) for more information. |
| devMode | string | `"false"` | Set dev mode for GraphQL requests; include the internal key in the errors extensions of the response (if required). |
| enableAllowlist | string | `"false"` | Restrict queries allowed to be executed by the GraphQL engine to those that are part of the configured allow-list. |
| enableApis | string | `"graphql,metadata"` | Comma separated list of APIs (options: metadata, graphql, pgdump, config) to be enabled. |
| enableApolloFederation | string | `"false"` | Enables the Apollo Federation feature. This allows Hasura to be connected as a subgraph in an Apollo supergraph. |
| enableConsole | string | `"true"` | Enable the Hasura Console (served by the server on / and /console). |
| enableLogCompression | string | `"false"` | Enable sending compressed logs to metrics server. |
| enableMaintainceMode | string | `"false"` | Disable updating of metadata on the server. |
| enableMetadataQueryLogging | string | `"false"` | Enables the query field in http-logs for metadata queries. |
| enableRemoteSchemaPermission | string | `"true"` | Enable remote schema permissions. |
| enableTelemetry | string | `"false"` | Enable anonymous telemetry. |
| enableTriggersErrorLogLevel | string | `"true"` | Sets the log-level as error for Trigger type error logs (Event Triggers, Scheduled Triggers, Cron Triggers). |
| enabledLogsType | string | `"http-log, webhook-log, websocket-log, query-log"` | Set the enabled log types. This is a comma-separated list of log-types to enable. |
| eventsFetchBatchSize | int | `100` | Maximum number of events to be fetched from the DB in a single batch. |
| eventsFetchInterval | string | `nil` | Interval in milliseconds to sleep before trying to fetch events again after a fetch returned no events from postgres |
| eventsHttpPoolSize | int | `100` | Maximum number of concurrent http workers delivering events at any time. |
| experimentalFeatures | string | `nil` | List of experimental features to be enabled. A comma separated value is expected. Options: inherited_roles, naming_convention, streaming_subscriptions. |
| extraEnvVarsCM | object | `{}` | extraEnvVarsCM append to ConfigMap with extra environment variables. See [values.yaml](values.yaml). # E.g: # extraEnvVarsCM: #   ENV_STORE_IN_CONFIGMAP: value |
| extraEnvVarsSecret | object | `{}` | extraEnvVarsSecret append to Secret with extra environment variables. See [values.yaml](values.yaml). # E.g: # extraEnvVarsSecret: #   ENV_STORE_IN_SECRET: value |
| fullnameOverride | string | `""` | A name to substitute for the full names of resources. |
| gracefulShutdownTimeout | int | `60` | Timeout (in seconds) to wait for the in-flight events (event triggers and scheduled triggers) and async actions to complete before the server shuts down completely. If the in-flight events are not completed within the timeout, those events are marked as pending. |
| image.pullPolicy | string | `"IfNotPresent"` | [Image pull policy](https://kubernetes.io/docs/concepts/containers/images/#updating-images) for updating already existing images on a node. |
| image.repository | string | `"hasura/graphql-engine"` | Name of the image repository to pull the container image from. |
| image.tag | string | `""` | Overrides the image tag whose default is the chart appVersion. |
| imagePullSecrets | list | `[]` | Reference to one or more secrets to be used when [pulling images](https://kubernetes.io/docs/tasks/configure-pod-container/pull-image-private-registry/#create-a-pod-that-uses-your-secret) (from private registries). |
| inferFunctionPermissions | string | `"true"` | When set to false, a function f, stable, immutable or volatile is only exposed for a role r if there is a permission defined on the function f for the role r, creating a function permission will only be allowed if there is a select permission on the table type. When set to true or the flag is omitted, the permission of the function is inferred from the select permissions from the target table of the function, only for stable/immutable functions. Volatile functions are not exposed to any of the roles in this case. |
| ingress.annotations | object | `{}` | Annotations to be added to the ingress. |
| ingress.className | string | `""` | Ingress [class name](https://kubernetes.io/docs/concepts/services-networking/ingress/#ingress-class). |
| ingress.enabled | bool | `false` | Enable [ingress](https://kubernetes.io/docs/concepts/services-networking/ingress/). |
| ingress.hosts | list | See [values.yaml](values.yaml). | Ingress host configuration. |
| ingress.tls | list | See [values.yaml](values.yaml). | Ingress TLS configuration. |
| jwtSecret | object | `{}` | An object map containing type and the JWK used for verifying (and other optional details). See the [JWT docs](https://hasura.io/docs/latest/graphql/core/auth/authentication/jwt.html). |
| liveQueriesMultiplexedBatchSize | int | `100` | Multiplexed live queries are split into batches of the specified size. |
| liveQueriesMultiplexedRefetchInterval | int | `1000` | Updated results (if any) will be sent at most once in this interval (in milliseconds) for live queries which can be multiplexed. |
| logLevel | string | `"info"` | Set the logging level. Options: debug, info, warn, error. |
| metadataDatabaseExtensionsSchema | string | `"public"` | The schema in which Hasura can install extensions in the metadata database. Default: public. |
| nameOverride | string | `""` | A name in place of the chart name for `app:` labels. |
| nodeSelector | object | `{}` | [Node selector](https://kubernetes.io/docs/concepts/scheduling-eviction/assign-pod-node/#nodeselector) configuration. |
| pgStripes | int | `1` | Number of stripes (distinct sub-pools) to maintain with Postgres. New connections will be taken from a particular stripe pseudo-randomly. |
| podAnnotations | object | `{}` | Annotations to be added to pods. |
| podLabels | object | `{}` | Labels to be added to pods. |
| podSecurityContext | object | `{}` | Pod [security context](https://kubernetes.io/docs/tasks/configure-pod-container/security-context/#set-the-security-context-for-a-pod). See the [API reference](https://kubernetes.io/docs/reference/kubernetes-api/workload-resources/pod-v1/#security-context) for details. |
| replicaCount | int | `1` | The number of replicas (pods) to launch |
| resources | object | No requests or limits. | Container resource [requests and limits](https://kubernetes.io/docs/concepts/configuration/manage-resources-containers/). See the [API reference](https://kubernetes.io/docs/reference/kubernetes-api/workload-resources/pod-v1/#resources) for details. |
| schemaSyncPollInterval | int | `1000` | Interval to poll metadata storage for updates in milliseconds - Set to 0 to disable. |
| secretName | string | `""` | secretName points to secret that is already created with environment variables such as: # HASURA_GRAPHQL_DATABASE_URL (required) # HASURA_GRAPHQL_ADMIN_SECRET (required) # HASURA_GRAPHQL_JWT_SECRET (optional) # HASURA_GRAPHQL_UNAUTHORIZED_ROLE (optional) |
| securityContext | object | `{}` | Container [security context](https://kubernetes.io/docs/tasks/configure-pod-container/security-context/#set-the-security-context-for-a-container). See the [API reference](https://kubernetes.io/docs/reference/kubernetes-api/workload-resources/pod-v1/#security-context-1) for details. |
| serverHost | string | `"*"` | Host on which graphql-engine will listen. |
| serverPort | int | `8080` | Port on which graphql-engine should be served. |
| service.annotations | object | `{}` | Annotations to be added to the service. |
| service.port | int | `80` | Service port. |
| service.type | string | `"ClusterIP"` | Kubernetes [service type](https://kubernetes.io/docs/concepts/services-networking/service/#publishing-services-service-types). |
| serviceAccount.annotations | object | `{}` | Annotations to add to the service account. |
| serviceAccount.create | bool | `true` | Specifies whether a service account should be created. |
| serviceAccount.name | string | `""` | The name of the service account to use. If not set and create is true, a name is generated using the fullname template. |
| streamingQueriesMultiplexedRefetchInterval | int | `1000` | For streaming queries which can be multiplexed, updated results - if any - will be sent, at most, once during this interval. |
| stringifyNumericTypes | string | `"false"` | Stringify certain Postgres numeric types, specifically bigint, numeric, decimal and double precision as they don’t fit into the IEEE-754 spec for JSON encoding-decoding. |
| tolerations | list | `[]` | [Tolerations](https://kubernetes.io/docs/concepts/scheduling-eviction/taint-and-toleration/) for node taints. See the [API reference](https://kubernetes.io/docs/reference/kubernetes-api/workload-resources/pod-v1/#scheduling) for details. |
| unauthorizedRole | string | `"anonymous"` | Unauthorized role, used when access-key is not sent in access-key only mode or the Authorization header is absent in JWT mode. Example: anonymous. Now whenever the “authorization” header is absent, the request’s role will default to anonymous. |
| v1BooleanNullCollapse | string | `nil` | Evaluate null values in where input object to True instead of error. |
| websocketConnectionInitTimeout | int | `3` | Used to set the connection initialisation timeout for graphql-ws clients. This is ignored for subscription-transport-ws (Apollo) clients. |
| websocketKeepalive | int | `5` | Used to set the Keep Alive delay for client that use the subscription-transport-ws (Apollo) protocol. For graphql-ws clients the graphql-engine sends PING messages instead. |
| wsReadCookie | string | `"false"` | Read cookie on WebSocket initial handshake even when CORS is disabled. |
