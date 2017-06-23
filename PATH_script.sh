#!/bin/bash
# CQA20 Android CTP´¥ÆÁÇý¶¯Ìí¼Ó£º

TOP_DIR=$(pwd)/../
ANDR_DIR=${TOP_DIR}android/device/softwinner/wing-k8x4/
LINUX_DIR=${TOP_DIR}lichee/linux-3.4/

# bakup
cp ${ANDR_DIR}init.sun7i.rc ${ANDR_DIR}init.sun7i.rc.bak0
cp ${LINUX_DIR}.config ${LINUX_DIR}.config.bak0

# copy config
cp init.sun7i.rc.ctp ${ANDR_DIR}init.sun7i.rc
cp config_ctp ${LINUX_DIR}.config
