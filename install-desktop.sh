#!/bin/bash

# ========================================
# Install Desktop Environment for Ubuntu 22.04
# ========================================

set -e

echo "======================================"
echo "Installing Desktop Environment"
echo "Ubuntu 22.04 - XFCE Desktop"
echo "======================================"
echo ""

# Update system
echo "1. Updating system packages..."
apt-get update
apt-get upgrade -y

# Install XFCE Desktop (lightweight)
echo ""
echo "2. Installing XFCE Desktop Environment..."
apt-get install -y xfce4 xfce4-goodies

# Install VNC Server
echo ""
echo "3. Installing TightVNC Server..."
apt-get install -y tightvncserver

# Install Firefox
echo ""
echo "4. Installing Firefox browser..."
apt-get install -y firefox

# Install essential tools
echo ""
echo "5. Installing essential tools..."
apt-get install -y \
    nano \
    vim \
    htop \
    net-tools \
    wget \
    curl

echo ""
echo "======================================"
echo "✓ Desktop Installation Complete!"
echo "======================================"
echo ""
echo "Next steps to setup VNC:"
echo ""
echo "1. Start VNC server (first time):"
echo "   vncserver"
echo ""
echo "2. Set VNC password when prompted"
echo ""
echo "3. Kill VNC server:"
echo "   vncserver -kill :1"
echo ""
echo "4. Configure VNC startup file:"
echo "   nano ~/.vnc/xstartup"
echo ""
echo "5. Add these lines to xstartup:"
echo "   #!/bin/bash"
echo "   xrdb \$HOME/.Xresources"
echo "   startxfce4 &"
echo ""
echo "6. Make it executable:"
echo "   chmod +x ~/.vnc/xstartup"
echo ""
echo "7. Start VNC server:"
echo "   vncserver -geometry 1920x1080 -depth 24"
echo ""
echo "8. Connect using VNC client to:"
echo "   YOUR_SERVER_IP:5901"
echo ""
echo "======================================"
