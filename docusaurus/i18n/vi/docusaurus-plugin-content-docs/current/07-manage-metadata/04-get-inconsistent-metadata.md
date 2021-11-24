---
id: get-inconsistent-metadata
title: Get inconsistent metadata
sidebar_title: Get inconsistent metadata
---

Trong trường hợp bạn vừa chạy database migrations có thêm, xóa columns hoặc các remote schema thay đổi SDL nhưng metadata thì lại tồn tại
các cấu hình trước đó thì sẽ xảy ra inconsistent metadata, bạn có thể [drop các inconsistent](./05-drop-inconsistent-metadata.md) hoặc 
xem các inconsistent thông qua command.

## Đối với Symfony users

Để xem các inconsistent bạn cần thực thi Symfony command sau:

```shell
php bin/console hasura:metadata:get-inconsistent
```

Command trên sẽ tổng hợp các lỗi inconsistent và xuất ra ngoài màn hình giúp bạn.