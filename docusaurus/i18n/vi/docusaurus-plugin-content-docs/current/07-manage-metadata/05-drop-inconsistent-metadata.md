---
id: drop-inconsistent-metadata
title: Drop inconsistent
sidebar_title: Drop inconsistent
---

Drop inconsistent metadata sẽ giúp bạn xóa đi các cấu hình metadata không hợp lệ (table column không tồn tại hoặc remote schema field không tồn tại...),
trước khi drop, chúng tôi khuyên bạn nên xem lại các cấu hình sẽ bị drop thông qua tài liệu [get inconsistent metadata](./04-get-inconsistent-metadata.md).

## Đối với Symfony users

Để drop các inconsistent metadata bạn cần thực thi Symfony command sau:

```shell
php bin/console hasura:metadata:drop-inconsistent
```

Command trên sẽ drop tất cả inconsistent metadata.