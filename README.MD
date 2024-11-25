# TailDown

TailDown是一个PHP编写的，多版本分发下载站程序，后台可以发布版本，前台会列出所有已发布版本+简介及下载链接，还有公告功能

<!-- PROJECT SHIELDS -->
[![Forks][forks-shield]][forks-url]
[![Stargazers][stars-shield]][stars-url]
[![Issues][issues-shield]][issues-url]
[![MIT License][license-shield]][license-url]

<!-- PROJECT LOGO -->
<br />

<p align="center">
  <a href="https://github.com/MengZe2l/taildown/">
    <img src="https://mirror.mengze2.cn/proxy/github.com/MengZe2l.png" alt="Logo" width="80" height="80">
  </a>

  <h3 align="center">"唯一的"项目分发程序</h3>
  <p align="center">
    一个基于PHP的完全开源免费的项目多版本分发站程序！
    <br />
    <a href="https://github.com/MengZe2l/taildown/"><strong>探索本项目的文档 »</strong></a>
    <br />
    <br />
    <a href="https://d.usfpack.site">查看Demo</a>
    ·
    <a href="https://github.com/MengZe2l/taildown/issues">报告Bug</a>
    ·
    <a href="https://github.com/MengZe2l/taildown/issues">提出新特性</a>
  </p>

</p>


 本篇README.MD面向网站运维者

## 目录

- [上手指南](#上手指南)
  - [开发前的配置要求](#开发前的配置要求)
  - [安装步骤](#安装步骤)
- [文件目录说明](#文件目录说明)
- [部署](#部署)
- [使用到的框架](#使用到的框架)
- [作者](#作者)
- [鸣谢](#鸣谢)

### 上手指南

该项目的开发一般是重写UI，本项目全部使用TailwindCSS2.2.19默认样式开发，无冗余CSS，若要重写UI，上手非常简单。

###### 开发前的配置要求

1. PHP7.4~8.3开发环境
2. Web环境

###### **安装步骤**

1. Clone the repo

```sh
git clone https://github.com/MengZe2l/taildown.git
```

### 文件目录说明

```filetree
PHP_WorkSpace
├── LICENSE
├── README.MD
├── /public/
│  ├── /admin/
│  │  ├── /uploads/
│  │  ├── announcements.php
│  │  ├── dashboard.php
│  │  ├── header.php
│  │  ├── index.php
│  │  ├── login.php
│  │  ├── settings.php
│  │  └── versions.php
│  ├── index.php
├── /src/
│  ├── auth.php
│  ├── database.php
│  ├── functions.php
│  └── helper.php
├── install.php
├── index.php

```

### 部署

克隆本仓库新建一个PHP站点，上传程序，访问首页，若没安装会转跳安装向导，安装好之后即可正常访问了。后台在域名/public/admin

### 使用到的框架

- [PHP](https://www.php.net/)
- [MySQL](https://www.mysql.com/)
- [TailwindCSS](https://www.tailwindcss.cn/)

### 作者

i@mengze2.cn

qq:2317113278

 *您也可以在贡献者名单中参看所有参与该项目的开发者。*

### 版权说明

该项目签署了MIT 授权许可，详情请参阅 [LICENSE](https://github.com/MengZe2l/taildown/blob/main/LICENSE)

### 鸣谢

- [Choose an Open Source License](https://choosealicense.com)
- [GitHub Pages](https://pages.github.com)


## 贡献者
<a href="https://github.com/MengZe2l">
  <img src="https://mirror.mengze2.cn/proxy/github.com/MengZe2l.png" />
</a>

#### 如何参与开源项目

贡献使开源社区成为一个学习、激励和创造的绝佳场所。你所作的任何贡献都是**非常感谢**的。


1. Fork 本项目，然后发布并在本仓库Issue里宣传
2. 直接在Issue提出你的代码
3. 联系作者

<!-- links -->
[your-project-path]: MengZe2l/taildown
[forks-shield]: https://img.shields.io/github/forks/MengZe2l/taildown.svg?style=flat-square
[forks-url]: https://github.com/MengZe2l/taildown/network/members
[stars-shield]: https://img.shields.io/github/stars/MengZe2l/taildown.svg?style=flat-square
[stars-url]: https://github.com/MengZe2l/taildown/stargazers
[issues-shield]: https://img.shields.io/github/issues/MengZe2l/taildown.svg?style=flat-square
[issues-url]: https://img.shields.io/github/issues/MengZe2l/taildown.svg
[license-shield]: https://img.shields.io/github/license/MengZe2l/taildown.svg?style=flat-square
[license-url]: https://github.com/MengZe2l/taildown/blob/main/LICENSE