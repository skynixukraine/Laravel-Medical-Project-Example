## Provision all servers

### Requirements
All public IPs of your production servers must be set in **hosts**.
Your .ssh/config must look like this:
```
Host ohn-prd1
  Hostname ec2-35-157-116-234.eu-central-1.compute.amazonaws.com
  IdentityFile /home/lx/.ssh/ohn.pem
  LocalForward 3311 prd.cxw6dnmfvalb.eu-central-1.rds.amazonaws.com:3306
  User ubuntu

Host ohn-prd2
  Hostname ec2-18-196-76-53.eu-central-1.compute.amazonaws.com
  IdentityFile /home/lx/.ssh/ohn2.pem
  User ubuntu
```

### how?
Run the provisioning script from your computer.
You can run it several times on the same servers.

```  
cd provision 

# run the entire provisioning
ansible-playbook provision.yml -i hosts -v

# only nginx tagged role
ansible-playbook provision.yml -i hosts --tags "nginx" -v
 ```