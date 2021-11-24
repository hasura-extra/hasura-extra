---
id: apply-metadata
title: Apply metadata
sidebar_title: Apply metadata
---

Sau khi đã thực thi [export metadata](./01-export-metadata.md) ra files và bây giờ chúng ta sẽ apply metadata trên các máy (môi trường)
khác nhau.

## Đối với Symfony users

Để thực thi apply metadata bạn cần thực thi Symfony command sau:

```shell
php bin/console hasura:metadata:apply
```

Trong trường hợp data inconsistent (do remote schema hoặc thiếu table columns...) nhưng bạn vẫn chấp nhận và tiếp tục thì hãy xài
option `--allow-inconsistent`:

```shell
php bin/console hasura:metadata:apply --allow-inconsistent
```

Vậy là bạn đã hoàn tất apply metadata rồi đấy.