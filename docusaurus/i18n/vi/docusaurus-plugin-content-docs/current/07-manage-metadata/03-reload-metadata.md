---
id: reload-metadata
title: Reload metadata
sidebar_title: Reload metadata
---

Sau khi database bạn xảy ra thay đổi (thêm/xóa columns) hoặc remote schema có thay đổi về SDL thì bạn cần reload lại metadata,
để cập nhật state mới nhất.

## Đối với Symfony users

Để reload metadata bạn cần thực thi Symfony command sau:

```shell
php bin/console hasura:metadata:reload
```

Đợi trong vài giây và việc reload metadata sẽ hoàn tất.