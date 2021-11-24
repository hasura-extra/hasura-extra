---
id: export-metadata
title: Export metadata
sidebar_title: Export metadata
---

Hasura metadata chứa các cấu hình data sources, remote schema, inherited roles, data federation, authorization... của hệ 
thống của bạn, việc export metadata ra files sẽ giúp bạn share metadata với các members khác trong team và môi trường production,
các files exported sẽ được sử dụng để [apply](./02-apply-metadata.md) trên nhiều môi trường khác nhau và đảm bảo tính consistent.

## Đối với Symfony users

Để export Hasura metadata bạn cần thực thi Symfony command sau:

```shell
php bin/console hasura:metadata:export
```

Sau khi chạy command, metadata sẽ được xuất ra files tại thư mục `hasura/metadata`, vậy là bạn đã hoàn tất export metadata rồi đấy.