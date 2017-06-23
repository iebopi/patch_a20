import init.sun7i.usb.rc

on init
    # See storage config details at http://source.android.com/tech/storage/
    mkdir /mnt/sdcard 0700 shell shell
    mkdir /mnt/extsd 0700 shell shell
    mkdir /mnt/usbhost 0700 shell shell

    symlink /mnt/sdcard /sdcard
    export EXTERNAL_STORAGE /mnt/sdcard

    # Support legacy paths
    mount debugfs debugfs /sys/kernel/debug
    mkdir /databk 0700 root  system
    
on early-fs
    mount_all /fstab.sun7i
    setprop ro.crypto.fuse_sdcard true
    insmod  /system/vendor/modules/disp.ko
    insmod  /system/vendor/modules/lcd.ko
    insmod  /system/vendor/modules/hdmi.ko
    insmod  /system/vendor/modules/hdcp.ko
    
# insmod mali driver
	#insmod /system/vendor/modules/ump.ko
    insmod /system/vendor/modules/mali.ko
    chmod 777 /dev/ump
    chmod 777 /dev/mali
    chown system system /sys/devices/platform/mali-utgard.0/aw_mali_freq/mali_clk
    chown system system /sys/devices/platform/mali-utgard.0/aw_mali_freq/mali_vol

on post-fs-data

on fs
#    format_userdata /dev/block/by-name/UDISK WING

on boot
# use automatic detecttion insmod ctp & gsensor driver
    insmod /system/vendor/modules/sw_device.ko

#insmod mac
    insmod /system/vendor/modules/sunxi_gmac.ko
# insmod video driver
    insmod /system/vendor/modules/cedarx.ko

# insmod security_system driver
    insmod /system/vendor/modules/security_system.ko

# csi module
    insmod /system/vendor/modules/videobuf-core.ko
    insmod /system/vendor/modules/videobuf-dma-contig.ko
    insmod /system/vendor/modules/camera.ko
    insmod /system/vendor/modules/ov5640.ko
    insmod /system/vendor/modules/gc0308.ko
    insmod /system/vendor/modules/sunxi_csi0.ko
    #insmod /system/vendor/modules/sun7i-ir.ko

# insmod network
    insmod /system/vendor/modules/usbnet.ko
    insmod /system/vendor/modules/asix.ko
    insmod /system/vendor/modules/qf9700.ko
    insmod /system/vendor/modules/mcs7830.ko
    insmod /system/vendor/modules/rtl8150.ko
    insmod /system/vendor/modules/8188eu.ko
    insmod /system/vendor/modules/cdc_ether.ko

# for usbtouchscreen
    #insmod /system/vendor/modules/usbtouchscreen.ko
    insmod /system/vendor/modules/hid-multitouch.ko
    insmod /system/vendor/modules/ilitek_ts.ko

chmod 0777 /dev/led
chmod 0777 /dev/ttyS0
chmod 0777 /dev/ttyS1
chmod 0777 /dev/ttyS2
chmod 0777 /dev/ttyS3
chmod 0777 /dev/ttyS4
chmod 0777 /dev/ttyS5

# bluetooth   
# UART device
#chmod 0777 /dev/ttyS2
#chown bluetooth net_bt_stack /dev/ttyS2
# power up/down interface
#chmod 0660 /sys/class/rfkill/rfkill0/state
#chmod 0660 /sys/class/rfkill/rfkill0/type
#chown bluetooth net_bt_stack /sys/class/rfkill/rfkill0/state
#chown bluetooth net_bt_stack /sys/class/rfkill/rfkill0/type
# bluetooth MAC address programming
#chown bluetooth net_bt_stack /system/etc/bluetooth
#chown bluetooth net_bt_stack /data/misc/bluetooth
#chown bluetooth net_bt_stack ro.bt.bdaddr_path
#setprop ro.bt.bdaddr_path "/system/etc/firmware/bd_addr.txt"

# bluetooth LPM
#chmod 0220 /proc/bluetooth/sleep/lpm
#chmod 0220 /proc/bluetooth/sleep/btwrite
#chown bluetooth net_bt_stack /proc/bluetooth/sleep/lpm
#chown bluetooth net_bt_stack /proc/bluetooth/sleep/btwrite

# 1. realtek wifi service
# 1.1 realtek wifi sta service
service wpa_supplicant /system/bin/logwrapper /system/bin/wpa_supplicant \
    -iwlan0 -Dnl80211 -c/data/misc/wifi/wpa_supplicant.conf \
    -O/data/misc/wifi/sockets \
    -e/data/misc/wifi/entropy.bin -g@android:wpa_wlan0
    class main
    socket wpa_wlan0 dgram 660 wifi wifi
    disabled
    oneshot

# 1.2 realtek wifi sta p2p concurrent service
service p2p_supplicant /system/bin/logwrapper /system/bin/wpa_supplicant \
    -ip2p0 -Dnl80211 -c/data/misc/wifi/p2p_supplicant.conf \
    -e/data/misc/wifi/entropy.bin -N \
    -iwlan0 -Dnl80211 -c/data/misc/wifi/wpa_supplicant.conf \
    -O/data/misc/wifi/sockets \
    -g@android:wpa_wlan0
    class main
    socket wpa_wlan0 dgram 660 wifi wifi
    disabled
    oneshot

# 2. broadcom wifi service
# 2.1 broadcom wifi bcm40181 bcm40183 station and softap
#service wpa_supplicant /system/bin/wpa_supplicant \
#   -iwlan0 -Dnl80211 -c/data/misc/wifi/wpa_supplicant.conf -e/data/misc/wifi/entropy.bin
#   class main
#   socket wpa_wlan0 dgram 660 wifi wifi
#   disabled
#   oneshot

# 2.2 braodcom wifi sta p2p concurrent service
#service p2p_supplicant /system/bin/wpa_supplicant \
#   -iwlan0 -Dnl80211 -c/data/misc/wifi/wpa_supplicant.conf -N \
#   -ip2p0 -Dnl80211 -c/data/misc/wifi/p2p_supplicant.conf -e/data/misc/wifi/entropy.bin -puse_p2p_group_interface=1
#   class main
#   socket wpa_wlan0 dgram 660 wifi wifi
#   disabled
#   oneshot
